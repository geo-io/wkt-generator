<?php

namespace GeoIO\WKT\Generator;

use GeoIO\Dimension;
use GeoIO\Extractor;
use GeoIO\WKT\Generator\Exception\GeneratorException;
use GeoIO\WKT\Generator\Exception\InvalidOptionException;

class Generator
{
    /**
     * SFS 1.1 WKT (i.e. no Z or M markers in the tags) but with Z and/or M
     * values added in if they are present.
     */
    const FORMAT_WKT11 = 'wkt11';

    /**
     * SFS 1.1 WKT with Z and M dropped from the output (since WKT strictly
     * does not support the Z or M dimensions).
     */
    const FORMAT_WKT11_STRICT = 'wkt11_strict';

    /**
     * SFS 1.2 WKT with Z, M and ZM markers in a separate token.
     */
    const FORMAT_WKT12 = 'wkt12';

    /**
     * PostGIS EWKT extension with M marker appended to tag names if M but not
     * Z is present.
     */
    const FORMAT_EWKT = 'ewkt';

    /**
     * Change all letters in the output to UPPER CASE.
     */
    const CASE_UPPER = 'uppercase';

    /**
     * Change all letters to lower case.
     */
    const CASE_LOWER = 'lowercase';

    /**
     * No case changes from the default (which is not specified exactly, but is
     * chosen by the generator to emphasize readability).
     */
    const CASE_NOACTION = null;

    private $extractor;

    private $sprintfFormat = '%F';

    private $options = array(
        'format' => self::FORMAT_WKT11,
        'emit_srid' => false, // Available only if format is FORMAT_EWKT
        'case' => self::CASE_NOACTION,
        'float_precision' => 6, // PHP default
    );

    public function __construct(Extractor $extractor, array $options = array())
    {
        $this->extractor = $extractor;

        if (isset($options['format'])) {
            switch ($options['format']) {
                case self::FORMAT_WKT11:
                case self::FORMAT_WKT11_STRICT:
                case self::FORMAT_WKT12:
                case self::FORMAT_EWKT:
                    $this->options['format'] = $options['format'];
                    break;
                default:
                    throw InvalidOptionException::create(
                        'format',
                        $options['format'],
                        array(
                            self::FORMAT_WKT11,
                            self::FORMAT_WKT11_STRICT,
                            self::FORMAT_WKT12,
                            self::FORMAT_EWKT
                        )
                    );
            }
        }

        if (isset($options['emit_srid']) && self::FORMAT_EWKT === $this->options['format']) {
            $this->options['emit_srid'] = (bool) $options['emit_srid'];
        }

        if (isset($options['case'])) {
            switch ($options['case']) {
                case self::CASE_UPPER:
                case self::CASE_LOWER:
                case self::CASE_NOACTION:
                    $this->options['case'] = $options['case'];
                    break;
                default:
                    throw InvalidOptionException::create(
                        'case',
                        $options['case'],
                        array(
                            self::CASE_UPPER,
                            self::CASE_LOWER,
                            self::CASE_NOACTION
                        )
                    );
            }
        }

        if (isset($options['float_precision'])) {
            $this->sprintfFormat = sprintf('%%.%dF', (int) $options['float_precision']);
        }
    }

    public function generate($geometry)
    {
        try {
            $str = '';

            if ($this->options['emit_srid'] &&
                null !== ($srid = $this->extractor->extractSrid($geometry))) {
                $str .= sprintf('SRID=%d;', $srid);
            }

            $dimension = $this->extractor->extractDimension($geometry);

            $str .= $this->generateGeometry($geometry, $dimension);

            switch ($this->options['case']) {
                case self::CASE_UPPER:
                    $str = strtoupper($str);
                    break;
                case self::CASE_LOWER:
                    $str = strtolower($str);
                    break;
            }

            return $str;
        } catch (\Exception $e) {
            throw new GeneratorException('Generation failed: ' . $e->getMessage(), 0, $e);
        }
    }

    private function generateGeometry($geometry, $dimension)
    {
        $type = $this->extractor->extractType($geometry);
        $data = $this->generateGeometryData($type, $dimension, $geometry);

        $str = $type;

        if (self::FORMAT_WKT12 === $this->options['format']) {
            switch ($dimension) {
                case Dimension::DIMENSION_4D:
                    return $str . ' ZM ' . $data;
                case Dimension::DIMENSION_3DM:
                    return $str . ' M ' . $data;
                case Dimension::DIMENSION_3DZ:
                    return $str . ' Z ' . $data;
            }
        }

        if (self::FORMAT_EWKT === $this->options['format']) {
            switch ($dimension) {
                case Dimension::DIMENSION_3DM:
                    $str .= 'M';
                    break;
            }
        }

        if ('EMPTY' === $data) {
            $str .= ' ';
        }

        return $str . $data;
    }

    private function generateGeometryData($type, $dimension, $geometry)
    {
        switch ($type) {
            case Extractor::TYPE_POINT:
                return $this->generatePoint($geometry, $dimension);
            case Extractor::TYPE_LINESTRING:
                return $this->generateLineString($geometry, $dimension);
            case Extractor::TYPE_POLYGON:
                return $this->generatePolygon($geometry, $dimension);
            case Extractor::TYPE_MULTIPOINT:
                return $this->generateMultiPoint($geometry, $dimension);
            case Extractor::TYPE_MULTILINESTRING:
                return $this->generateMultiLineString($geometry, $dimension);
            case Extractor::TYPE_MULTIPOLYGON:
                return $this->generateMultiPolygon($geometry, $dimension);
            default:
                return $this->generateGeometryCollection($geometry, $dimension);
        }
    }

    private function generateCoordinates(array $coordinates, $dimension)
    {
        $x = isset($coordinates['x']) ? $coordinates['x'] : 0;
        $y = isset($coordinates['y']) ? $coordinates['y'] : 0;

        $str = sprintf($this->sprintfFormat . ' ' . $this->sprintfFormat, $x, $y);

        if (self::FORMAT_WKT11_STRICT !== $this->options['format'] &&
            (Dimension::DIMENSION_4D === $dimension ||
             Dimension::DIMENSION_3DZ === $dimension)) {
            $z = isset($coordinates['z']) ? $coordinates['z'] : 0;
            $str .= ' ' . sprintf($this->sprintfFormat, $z);
        }

        if (self::FORMAT_WKT11_STRICT !== $this->options['format'] &&
            (Dimension::DIMENSION_4D === $dimension ||
             Dimension::DIMENSION_3DM === $dimension)) {
            $m = isset($coordinates['m']) ? $coordinates['m'] : 0;
            $str .= ' ' . sprintf($this->sprintfFormat, $m);
        }

        return $str;
    }

    private function generatePoint($point, $dimension)
    {
        $coordinates = $this->extractor->extractCoordinatesFromPoint($point);

        if (!$coordinates) {
            return 'EMPTY';
        }

        return sprintf('(%s)', $this->generateCoordinates($coordinates, $dimension));
    }

    private function generateLineString($lineString, $dimension)
    {
        $points = $this->extractor->extractPointsFromLineString($lineString);

        $parts = array();
        foreach ($points as $point) {
            $coordinates = $this->extractor->extractCoordinatesFromPoint($point);
            $parts[] = $this->generateCoordinates($coordinates, $dimension);
        }

        if (!$parts) {
            return 'EMPTY';
        }

        return sprintf('(%s)', implode(', ', $parts));
    }

    private function generatePolygon($polygon, $dimension)
    {
        $lineStrings = $this->extractor->extractLineStringsFromPolygon($polygon);

        $parts = array();
        foreach ($lineStrings as $lineString) {
            $parts[] = $this->generateLineString($lineString, $dimension);
        }

        if (!$parts) {
            return 'EMPTY';
        }

        return sprintf('(%s)', implode(', ', $parts));
    }

    private function generateMultiPoint($multiPoint, $dimension)
    {
        $points = $this->extractor->extractPointsFromMultiPoint($multiPoint);

        $parts = array();
        foreach ($points as $point) {
            $parts[] = $this->generatePoint($point, $dimension);
        }

        if (!$parts) {
            return 'EMPTY';
        }

        return sprintf('(%s)', implode(', ', $parts));
    }

    private function generateMultiLineString($multiLineString, $dimension)
    {
        $lineStrings = $this->extractor->extractLineStringsFromMultiLineString($multiLineString);

        $parts = array();
        foreach ($lineStrings as $lineString) {
            $parts[] = $this->generateLineString($lineString, $dimension);
        }

        if (!$parts) {
            return 'EMPTY';
        }

        return sprintf('(%s)', implode(', ', $parts));
    }

    private function generateMultiPolygon($multiPolygon, $dimension)
    {
        $polygons = $this->extractor->extractPolygonsFromMultiPolygon($multiPolygon);

        $parts = array();
        foreach ($polygons as $polygon) {
            $parts[] = $this->generatePolygon($polygon, $dimension);
        }

        if (!$parts) {
            return 'EMPTY';
        }

        return sprintf('(%s)', implode(', ', $parts));
    }

    private function generateGeometryCollection($geometryCollection, $dimension)
    {
        $geometries = $this->extractor->extractGeometriesFromGeometryCollection($geometryCollection);

        $parts = array();
        foreach ($geometries as $geometry) {
            $parts[] = $this->generateGeometry($geometry, $dimension);
        }

        if (!$parts) {
            return 'EMPTY';
        }

        return sprintf('(%s)', implode(', ', $parts));
    }
}
