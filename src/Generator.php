<?php

declare(strict_types=1);

namespace GeoIO\WKT\Generator;

use Exception;
use GeoIO\Coordinates;
use GeoIO\Dimension;
use GeoIO\Extractor;
use GeoIO\GeometryType;
use GeoIO\WKT\Generator\Exception\GeneratorException;
use GeoIO\WKT\Generator\Exception\InvalidOptionException;

final class Generator
{
    /**
     * SFS 1.1 WKT (i.e. no Z or M markers in the tags) but with Z and/or M
     * values added in if they are present.
     */
    public const FORMAT_WKT11 = 'wkt11';

    /**
     * SFS 1.1 WKT with Z and M dropped from the output (since WKT strictly
     * does not support the Z or M dimensions).
     */
    public const FORMAT_WKT11_STRICT = 'wkt11_strict';

    /**
     * SFS 1.2 WKT with Z, M and ZM markers in a separate token.
     */
    public const FORMAT_WKT12 = 'wkt12';

    /**
     * PostGIS EWKT extension with M marker appended to tag names if M but not
     * Z is present.
     */
    public const FORMAT_EWKT = 'ewkt';

    /**
     * Change all letters in the output to UPPER CASE.
     */
    public const CASE_UPPER = 'uppercase';

    /**
     * Change all letters to lower case.
     */
    public const CASE_LOWER = 'lowercase';

    /**
     * No case changes from the default (which is not specified exactly, but is
     * chosen by the generator to emphasize readability).
     */
    public const CASE_NOACTION = null;

    private Extractor $extractor;

    private string $sprintfFormat = '%F';

    private array $options = [
        'format' => self::FORMAT_WKT11,
        'emit_srid' => false, // Available only if format is FORMAT_EWKT
        'case' => self::CASE_NOACTION,
        'float_precision' => 6, // PHP default
    ];

    public function __construct(
        Extractor $extractor,
        array $options = [],
    ) {
        $this->extractor = $extractor;

        if (isset($options['format'])) {
            $this->options['format'] = match ($options['format']) {
                self::FORMAT_WKT11, self::FORMAT_WKT11_STRICT, self::FORMAT_WKT12, self::FORMAT_EWKT => $options['format'],
                default => throw InvalidOptionException::create(
                    'format',
                    $options['format'],
                    [
                        self::FORMAT_WKT11,
                        self::FORMAT_WKT11_STRICT,
                        self::FORMAT_WKT12,
                        self::FORMAT_EWKT,
                    ],
                ),
            };
        }

        if (
            isset($options['emit_srid']) &&
            self::FORMAT_EWKT === $this->options['format']
        ) {
            $this->options['emit_srid'] = (bool) $options['emit_srid'];
        }

        if (isset($options['case'])) {
            $this->options['case'] = match ($options['case']) {
                self::CASE_UPPER, self::CASE_LOWER, self::CASE_NOACTION => $options['case'],
                default => throw InvalidOptionException::create(
                    'case',
                    $options['case'],
                    [
                        self::CASE_UPPER,
                        self::CASE_LOWER,
                        self::CASE_NOACTION,
                    ],
                ),
            };
        }

        if (isset($options['float_precision'])) {
            $this->sprintfFormat = sprintf(
                '%%.%dF',
                (int) $options['float_precision'],
            );
        }
    }

    public function generate(mixed $geometry): string
    {
        try {
            $str = '';

            if (
                $this->options['emit_srid'] &&
                null !== ($srid = $this->extractor->extractSrid($geometry))
            ) {
                $str .= sprintf('SRID=%d;', $srid);
            }

            $dimension = $this->extractor->extractDimension($geometry);

            $str .= $this->generateGeometry($geometry, $dimension);

            return match ($this->options['case']) {
                self::CASE_UPPER => strtoupper($str),
                self::CASE_LOWER => strtolower($str),
                default => $str,
            };
        } catch (Exception $e) {
            throw new GeneratorException(
                'Generation failed: ' . $e->getMessage(),
                0,
                $e,
            );
        }
    }

    private function generateGeometry(
        mixed $geometry,
        Dimension $dimension,
    ): string {
        $type = $this->extractor->extractType($geometry);
        $data = $this->generateGeometryData($type, $dimension, $geometry);

        $str = $type->value;

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

    private function generateGeometryData(
        GeometryType $type,
        Dimension $dimension,
        mixed $geometry,
    ): string {
        return match ($type) {
            GeometryType::POINT => $this->generatePoint($geometry, $dimension),
            GeometryType::LINESTRING => $this->generateLineString($geometry, $dimension),
            GeometryType::POLYGON => $this->generatePolygon($geometry, $dimension),
            GeometryType::MULTIPOINT => $this->generateMultiPoint($geometry, $dimension),
            GeometryType::MULTILINESTRING => $this->generateMultiLineString($geometry, $dimension),
            GeometryType::MULTIPOLYGON => $this->generateMultiPolygon($geometry, $dimension),
            default => $this->generateGeometryCollection($geometry, $dimension),
        };
    }

    private function generateCoordinates(
        ?Coordinates $coordinates,
        Dimension $dimension,
    ): string {
        $x = $coordinates->x ?? 0.0;
        $y = $coordinates->y ?? 0.0;

        $str = sprintf(
            $this->sprintfFormat . ' ' . $this->sprintfFormat,
            $x,
            $y,
        );

        if (
            (
                Dimension::DIMENSION_4D === $dimension ||
                Dimension::DIMENSION_3DZ === $dimension
            ) &&
            self::FORMAT_WKT11_STRICT !== $this->options['format']
        ) {
            $z = $coordinates->z ?? 0.0;
            $str .= ' ' . sprintf($this->sprintfFormat, $z);
        }

        if (
            (
                Dimension::DIMENSION_4D === $dimension ||
                Dimension::DIMENSION_3DM === $dimension
            ) &&
            self::FORMAT_WKT11_STRICT !== $this->options['format']
        ) {
            $m = $coordinates->m ?? 0.0;
            $str .= ' ' . sprintf($this->sprintfFormat, $m);
        }

        return $str;
    }

    private function generatePoint(
        mixed $point,
        Dimension $dimension,
    ): string {
        $coordinates = $this->extractor->extractCoordinatesFromPoint(
            $point,
        );

        if (!$coordinates) {
            return 'EMPTY';
        }

        return sprintf(
            '(%s)',
            $this->generateCoordinates($coordinates, $dimension),
        );
    }

    private function generateLineString(
        mixed $lineString,
        Dimension $dimension,
    ): string {
        $points = $this->extractor->extractPointsFromLineString(
            $lineString,
        );

        $parts = [];

        /** @var mixed $point */
        foreach ($points as $point) {
            $coordinates = $this->extractor->extractCoordinatesFromPoint(
                $point,
            );

            $parts[] = $this->generateCoordinates($coordinates, $dimension);
        }

        if (!$parts) {
            return 'EMPTY';
        }

        return sprintf('(%s)', implode(', ', $parts));
    }

    private function generatePolygon(
        mixed $polygon,
        Dimension $dimension,
    ): string {
        $lineStrings = $this->extractor->extractLineStringsFromPolygon(
            $polygon,
        );

        $parts = [];

        /** @var mixed $lineString */
        foreach ($lineStrings as $lineString) {
            $parts[] = $this->generateLineString($lineString, $dimension);
        }

        if (!$parts) {
            return 'EMPTY';
        }

        return sprintf('(%s)', implode(', ', $parts));
    }

    private function generateMultiPoint(
        mixed $multiPoint,
        Dimension $dimension,
    ): string {
        $points = $this->extractor->extractPointsFromMultiPoint(
            $multiPoint,
        );

        $parts = [];

        /** @var mixed $point */
        foreach ($points as $point) {
            $parts[] = $this->generatePoint($point, $dimension);
        }

        if (!$parts) {
            return 'EMPTY';
        }

        return sprintf('(%s)', implode(', ', $parts));
    }

    private function generateMultiLineString(
        mixed $multiLineString,
        Dimension $dimension,
    ): string {
        $lineStrings = $this->extractor->extractLineStringsFromMultiLineString(
            $multiLineString,
        );

        $parts = [];

        /** @var mixed $lineString */
        foreach ($lineStrings as $lineString) {
            $parts[] = $this->generateLineString($lineString, $dimension);
        }

        if (!$parts) {
            return 'EMPTY';
        }

        return sprintf('(%s)', implode(', ', $parts));
    }

    private function generateMultiPolygon(
        mixed $multiPolygon,
        Dimension $dimension,
    ): string {
        $polygons = $this->extractor->extractPolygonsFromMultiPolygon(
            $multiPolygon,
        );

        $parts = [];

        /** @var mixed $polygon */
        foreach ($polygons as $polygon) {
            $parts[] = $this->generatePolygon($polygon, $dimension);
        }

        if (!$parts) {
            return 'EMPTY';
        }

        return sprintf('(%s)', implode(', ', $parts));
    }

    private function generateGeometryCollection(
        mixed $geometryCollection,
        Dimension $dimension,
    ): string {
        $geometries = $this->extractor->extractGeometriesFromGeometryCollection(
            $geometryCollection,
        );

        $parts = [];

        /** @var mixed $geometry */
        foreach ($geometries as $geometry) {
            $parts[] = $this->generateGeometry($geometry, $dimension);
        }

        if (!$parts) {
            return 'EMPTY';
        }

        return sprintf('(%s)', implode(', ', $parts));
    }
}
