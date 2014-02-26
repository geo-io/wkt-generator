<?php

namespace GeoIO\WKT\Generator;

use GeoIO\Dimension;
use GeoIO\Extractor;
use Mockery;

class GeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testPoint()
    {
        $point = new \stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_2D,
            $this->coords(1, 2)
        );

        $expected = sprintf('Point(%F %F)', 1, 2);

        $generator = new Generator($extractor);
        $this->assertSame($expected, $generator->generate($point));
    }

    public function testPointEmpty()
    {
        $point = new \stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_2D,
            null
        );

        $generator = new Generator($extractor);
        $this->assertSame('Point EMPTY', $generator->generate($point));
    }

    public function testPointZ()
    {
        $point = new \stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_3DZ,
            $this->coords(1, 2, 3)
        );

        $expected = sprintf('Point(%F %F %F)', 1, 2, 3);

        $generator = new Generator($extractor);
        $this->assertSame($expected, $generator->generate($point));
    }

    public function testPointM()
    {
        $point = new \stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_3DM,
            $this->coords(1, 2, null, 3)
        );

        $expected = sprintf('Point(%F %F %F)', 1, 2, 3);

        $generator = new Generator($extractor);
        $this->assertSame($expected, $generator->generate($point));
    }

    public function testPointZM()
    {
        $point = new \stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_4D,
            $this->coords(1, 2, 3, 4)
        );

        $expected = sprintf('Point(%F %F %F %F)', 1, 2, 3, 4);

        $generator = new Generator($extractor);
        $this->assertSame($expected, $generator->generate($point));
    }

    public function testPointUpperCase()
    {
        $point = new \stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_2D,
            $this->coords(1, 2)
        );

        $expected = sprintf('POINT(%F %F)', 1, 2);

        $generator = new Generator($extractor, array(
            'case' => Generator::CASE_UPPER
        ));

        $this->assertSame($expected, $generator->generate($point));
    }

    public function testPointLowerCase()
    {
        $point = new \stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_2D,
            $this->coords(1, 2)
        );

        $expected = sprintf('point(%F %F)', 1, 2);

        $generator = new Generator($extractor, array(
            'case' => Generator::CASE_LOWER
        ));

        $this->assertSame($expected, $generator->generate($point));
    }

    public function testPointWkt11Strict()
    {
        $point = new \stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_4D,
            $this->coords(1, 2, 3, 4)
        );

        $expected = sprintf('Point(%F %F)', 1, 2);

        $generator = new Generator($extractor, array(
            'format' => Generator::FORMAT_WKT11_STRICT
        ));

        $this->assertSame($expected, $generator->generate($point));
    }

    public function testPointWkt12()
    {
        $point = new \stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_2D,
            $this->coords(1, 2)
        );

        $expected = sprintf('Point(%F %F)', 1, 2);

        $generator = new Generator($extractor, array(
            'format' => Generator::FORMAT_WKT12
        ));

        $this->assertSame($expected, $generator->generate($point));
    }

    public function testPointWkt12Empty()
    {
        $point = new \stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_2D,
            null
        );

        $generator = new Generator($extractor, array(
            'format' => Generator::FORMAT_WKT12
        ));

        $this->assertSame('Point EMPTY', $generator->generate($point));
    }

    public function testPointWkt12Z()
    {
        $point = new \stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_3DZ,
            $this->coords(1, 2, 3)
        );

        $expected = sprintf('Point Z (%F %F %F)', 1, 2, 3);

        $generator = new Generator($extractor, array(
            'format' => Generator::FORMAT_WKT12
        ));
        $this->assertSame($expected, $generator->generate($point));
    }

    public function testPointWkt12M()
    {
        $point = new \stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_3DM,
            $this->coords(1, 2, null, 3)
        );

        $expected = sprintf('Point M (%F %F %F)', 1, 2, 3);

        $generator = new Generator($extractor, array(
            'format' => Generator::FORMAT_WKT12
        ));
        $this->assertSame($expected, $generator->generate($point));
    }

    public function testPointWkt12ZM()
    {
        $point = new \stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_4D,
            $this->coords(1, 2, 3, 4)
        );

        $expected = sprintf('Point ZM (%F %F %F %F)', 1, 2, 3, 4);

        $generator = new Generator($extractor, array(
            'format' => Generator::FORMAT_WKT12
        ));
        $this->assertSame($expected, $generator->generate($point));
    }

    public function testPointEwkt()
    {
        $point = new \stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_2D,
            $this->coords(1, 2)
        );

        $expected = sprintf('Point(%F %F)', 1, 2);

        $generator = new Generator($extractor, array(
            'format' => Generator::FORMAT_EWKT
        ));

        $this->assertSame($expected, $generator->generate($point));
    }

    public function testPointEwktEmpty()
    {
        $point = new \stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_2D,
            null
        );

        $generator = new Generator($extractor, array(
            'format' => Generator::FORMAT_EWKT
        ));

        $this->assertSame('Point EMPTY', $generator->generate($point));
    }

    public function testPointEwktZ()
    {
        $point = new \stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_3DZ,
            $this->coords(1, 2, 3)
        );

        $expected = sprintf('Point(%F %F %F)', 1, 2, 3);

        $generator = new Generator($extractor, array(
            'format' => Generator::FORMAT_EWKT
        ));
        $this->assertSame($expected, $generator->generate($point));
    }

    public function testPointEwktM()
    {
        $point = new \stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_3DM,
            $this->coords(1, 2, null, 3)
        );

        $expected = sprintf('PointM(%F %F %F)', 1, 2, 3);

        $generator = new Generator($extractor, array(
            'format' => Generator::FORMAT_EWKT
        ));
        $this->assertSame($expected, $generator->generate($point));
    }

    public function testPointEwktZM()
    {
        $point = new \stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_4D,
            $this->coords(1, 2, 3, 4)
        );

        $expected = sprintf('Point(%F %F %F %F)', 1, 2, 3, 4);

        $generator = new Generator($extractor, array(
            'format' => Generator::FORMAT_EWKT
        ));
        $this->assertSame($expected, $generator->generate($point));
    }

    public function testPointEwktWithSrid()
    {
        $point = new \stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_2D,
            $this->coords(1, 2),
            1234
        );

        $expected = sprintf('SRID=1234;Point(%F %F)', 1, 2);

        $generator = new Generator($extractor, array(
            'format' => Generator::FORMAT_EWKT,
            'emit_srid' => true
        ));

        $this->assertSame($expected, $generator->generate($point));
    }

    public function testLineString()
    {
        $lineString = new \stdClass();
        $point1 = new \stdClass();
        $point2 = new \stdClass();
        $point3 = new \stdClass();

        $extractor = Mockery::mock('GeoIO\\Extractor');

        $extractor
            ->shouldReceive('extractType')
            ->once()
            ->with($lineString)
            ->andReturn(Extractor::TYPE_LINESTRING)
        ;

        $extractor
            ->shouldReceive('extractDimension')
            ->once()
            ->with($lineString)
            ->andReturn(Dimension::DIMENSION_2D)
        ;

        $extractor
            ->shouldReceive('extractPointsFromLineString')
            ->once()
            ->with($lineString)
            ->andReturn(array($point1, $point2, $point3))
        ;

        $extractor
            ->shouldReceive('extractCoordinatesFromPoint')
            ->times(3)
            ->andReturn(
                $this->coords(1, 2),
                $this->coords(2, 2),
                $this->coords(1, 1)
            )
        ;

        $expected = sprintf('LineString(%F %F, %F %F, %F %F)', 1, 2, 2, 2, 1, 1);

        $generator = new Generator($extractor);

        $this->assertSame($expected, $generator->generate($lineString));
    }

    public function testLineStringEmpty()
    {
        $lineString = new \stdClass();

        $extractor = Mockery::mock('GeoIO\\Extractor');

        $extractor
            ->shouldReceive('extractType')
            ->once()
            ->with($lineString)
            ->andReturn(Extractor::TYPE_LINESTRING)
        ;

        $extractor
            ->shouldReceive('extractDimension')
            ->once()
            ->with($lineString)
            ->andReturn(Dimension::DIMENSION_2D)
        ;

        $extractor
            ->shouldReceive('extractPointsFromLineString')
            ->once()
            ->with($lineString)
            ->andReturn(array())
        ;

        $generator = new Generator($extractor);

        $this->assertSame('LineString EMPTY', $generator->generate($lineString));
    }

    public function testPolygon()
    {
        $polygon = new \stdClass();
        $lineString = new \stdClass();
        $point1 = new \stdClass();
        $point2 = new \stdClass();
        $point3 = new \stdClass();

        $extractor = Mockery::mock('GeoIO\\Extractor');

        $extractor
            ->shouldReceive('extractType')
            ->once()
            ->with($polygon)
            ->andReturn(Extractor::TYPE_POLYGON)
        ;

        $extractor
            ->shouldReceive('extractDimension')
            ->once()
            ->with($polygon)
            ->andReturn(Dimension::DIMENSION_2D)
        ;

        $extractor
            ->shouldReceive('extractLineStringsFromPolygon')
            ->once()
            ->with($polygon)
            ->andReturn(array($lineString))
        ;

        $extractor
            ->shouldReceive('extractPointsFromLineString')
            ->once()
            ->with($lineString)
            ->andReturn(array($point1, $point2, $point3))
        ;

        $extractor
            ->shouldReceive('extractCoordinatesFromPoint')
            ->times(3)
            ->andReturn(
                $this->coords(1, 2),
                $this->coords(2, 2),
                $this->coords(1, 1)
            )
        ;

        $expected = sprintf('Polygon((%F %F, %F %F, %F %F))', 1, 2, 2, 2, 1, 1);

        $generator = new Generator($extractor);

        $this->assertSame($expected, $generator->generate($polygon));
    }

    public function testPolygonWithHole()
    {
        $polygon = new \stdClass();
        $lineString1 = new \stdClass();
        $lineString2 = new \stdClass();
        $point1 = new \stdClass();
        $point2 = new \stdClass();
        $point3 = new \stdClass();
        $point4 = new \stdClass();
        $point5 = new \stdClass();
        $point6 = new \stdClass();
        $point7 = new \stdClass();
        $point8 = new \stdClass();
        $point9 = new \stdClass();

        $extractor = Mockery::mock('GeoIO\\Extractor');

        $extractor
            ->shouldReceive('extractType')
            ->once()
            ->with($polygon)
            ->andReturn(Extractor::TYPE_POLYGON)
        ;

        $extractor
            ->shouldReceive('extractDimension')
            ->once()
            ->with($polygon)
            ->andReturn(Dimension::DIMENSION_2D)
        ;

        $extractor
            ->shouldReceive('extractLineStringsFromPolygon')
            ->once()
            ->with($polygon)
            ->andReturn(array($lineString1, $lineString2))
        ;

        $extractor
            ->shouldReceive('extractPointsFromLineString')
            ->once()
            ->with($lineString1)
            ->andReturn(array($point1, $point2, $point3, $point4, $point5))
        ;

        $extractor
            ->shouldReceive('extractPointsFromLineString')
            ->once()
            ->with($lineString2)
            ->andReturn(array($point6, $point7, $point8, $point9))
        ;

        $extractor
            ->shouldReceive('extractCoordinatesFromPoint')
            ->times(9)
            ->andReturn(
                $this->coords(0, 0),
                $this->coords(10, 0),
                $this->coords(10, 10),
                $this->coords(0, 10),
                $this->coords(0, 0),

                $this->coords(1, 1),
                $this->coords(2, 2),
                $this->coords(3, 1),
                $this->coords(1, 1)
            )
        ;

        $expected = sprintf('Polygon((%F %F, %F %F, %F %F, %F %F, %F %F), (%F %F, %F %F, %F %F, %F %F))', 0, 0, 10, 0, 10, 10, 0, 10, 0, 0, 1, 1, 2, 2, 3, 1, 1, 1);

        $generator = new Generator($extractor);

        $this->assertSame($expected, $generator->generate($polygon));
    }

    public function testPolygonEmpty()
    {
        $polygon = new \stdClass();

        $extractor = Mockery::mock('GeoIO\\Extractor');

        $extractor
            ->shouldReceive('extractType')
            ->once()
            ->with($polygon)
            ->andReturn(Extractor::TYPE_POLYGON)
        ;

        $extractor
            ->shouldReceive('extractDimension')
            ->once()
            ->with($polygon)
            ->andReturn(Dimension::DIMENSION_2D)
        ;

        $extractor
            ->shouldReceive('extractLineStringsFromPolygon')
            ->once()
            ->with($polygon)
            ->andReturn(array())
        ;

        $generator = new Generator($extractor);

        $this->assertSame('Polygon EMPTY', $generator->generate($polygon));
    }

    public function testMultiPoint()
    {
        $multiPoint = new \stdClass();
        $point1 = new \stdClass();
        $point2 = new \stdClass();
        $point3 = new \stdClass();

        $extractor = Mockery::mock('GeoIO\\Extractor');

        $extractor
            ->shouldReceive('extractType')
            ->once()
            ->with($multiPoint)
            ->andReturn(Extractor::TYPE_MULTIPOINT)
        ;

        $extractor
            ->shouldReceive('extractDimension')
            ->once()
            ->with($multiPoint)
            ->andReturn(Dimension::DIMENSION_2D)
        ;

        $extractor
            ->shouldReceive('extractPointsFromMultiPoint')
            ->once()
            ->with($multiPoint)
            ->andReturn(array($point1, $point2, $point3))
        ;

        $extractor
            ->shouldReceive('extractCoordinatesFromPoint')
            ->times(3)
            ->andReturn(
                $this->coords(1, 2),
                $this->coords(2, 2),
                $this->coords(1, 1)
            )
        ;

        $expected = sprintf('MultiPoint((%F %F), (%F %F), (%F %F))', 1, 2, 2, 2, 1, 1);

        $generator = new Generator($extractor);

        $this->assertSame($expected, $generator->generate($multiPoint));
    }

    public function testMultiPointEmpty()
    {
        $multiPoint = new \stdClass();

        $extractor = Mockery::mock('GeoIO\\Extractor');

        $extractor
            ->shouldReceive('extractType')
            ->once()
            ->with($multiPoint)
            ->andReturn(Extractor::TYPE_MULTIPOINT)
        ;

        $extractor
            ->shouldReceive('extractDimension')
            ->once()
            ->with($multiPoint)
            ->andReturn(Dimension::DIMENSION_2D)
        ;

        $extractor
            ->shouldReceive('extractPointsFromMultiPoint')
            ->once()
            ->with($multiPoint)
            ->andReturn(array())
        ;

        $generator = new Generator($extractor);

        $this->assertSame('MultiPoint EMPTY', $generator->generate($multiPoint));
    }

    public function testMultiLineString()
    {
        $multiLineString = new \stdClass();
        $lineString1 = new \stdClass();
        $lineString2 = new \stdClass();
        $lineString3 = new \stdClass();
        $point1 = new \stdClass();
        $point2 = new \stdClass();
        $point3 = new \stdClass();
        $point4 = new \stdClass();
        $point5 = new \stdClass();
        $point6 = new \stdClass();
        $point7 = new \stdClass();
        $point8 = new \stdClass();
        $point9 = new \stdClass();

        $extractor = Mockery::mock('GeoIO\\Extractor');

        $extractor
            ->shouldReceive('extractType')
            ->once()
            ->with($multiLineString)
            ->andReturn(Extractor::TYPE_MULTILINESTRING)
        ;

        $extractor
            ->shouldReceive('extractDimension')
            ->once()
            ->with($multiLineString)
            ->andReturn(Dimension::DIMENSION_2D)
        ;

        $extractor
            ->shouldReceive('extractLineStringsFromMultiLineString')
            ->once()
            ->with($multiLineString)
            ->andReturn(array($lineString1, $lineString2, $lineString3))
        ;

        $extractor
            ->shouldReceive('extractPointsFromLineString')
            ->once()
            ->with($lineString1)
            ->andReturn(array($point1, $point2, $point3, $point4, $point5))
        ;

        $extractor
            ->shouldReceive('extractPointsFromLineString')
            ->once()
            ->with($lineString2)
            ->andReturn(array($point6, $point7, $point8, $point9))
        ;

        $extractor
            ->shouldReceive('extractPointsFromLineString')
            ->once()
            ->with($lineString3)
            ->andReturn(array())
        ;

        $extractor
            ->shouldReceive('extractCoordinatesFromPoint')
            ->times(9)
            ->andReturn(
                $this->coords(0, 0),
                $this->coords(10, 0),
                $this->coords(10, 10),
                $this->coords(0, 10),
                $this->coords(0, 0),

                $this->coords(1, 1),
                $this->coords(2, 2),
                $this->coords(3, 1),
                $this->coords(1, 1)
            )
        ;

        $expected = sprintf('MultiLineString((%F %F, %F %F, %F %F, %F %F, %F %F), (%F %F, %F %F, %F %F, %F %F), EMPTY)', 0, 0, 10, 0, 10, 10, 0, 10, 0, 0, 1, 1, 2, 2, 3, 1, 1, 1);

        $generator = new Generator($extractor);

        $this->assertSame($expected, $generator->generate($multiLineString));
    }

    public function testMultiLineStringEmpty()
    {
        $multiLineString = new \stdClass();

        $extractor = Mockery::mock('GeoIO\\Extractor');

        $extractor
            ->shouldReceive('extractType')
            ->once()
            ->with($multiLineString)
            ->andReturn(Extractor::TYPE_MULTILINESTRING)
        ;

        $extractor
            ->shouldReceive('extractDimension')
            ->once()
            ->with($multiLineString)
            ->andReturn(Dimension::DIMENSION_2D)
        ;

        $extractor
            ->shouldReceive('extractLineStringsFromMultiLineString')
            ->once()
            ->with($multiLineString)
            ->andReturn(array())
        ;

        $generator = new Generator($extractor);

        $this->assertSame('MultiLineString EMPTY', $generator->generate($multiLineString));
    }

    public function testMultiPolygon()
    {
        $multiPolygon = new \stdClass();
        $polygon1 = new \stdClass();
        $polygon2 = new \stdClass();
        $polygon3 = new \stdClass();
        $lineString1 = new \stdClass();
        $lineString2 = new \stdClass();
        $lineString3 = new \stdClass();
        $point1 = new \stdClass();
        $point2 = new \stdClass();
        $point3 = new \stdClass();
        $point4 = new \stdClass();
        $point5 = new \stdClass();
        $point6 = new \stdClass();
        $point7 = new \stdClass();
        $point8 = new \stdClass();
        $point9 = new \stdClass();
        $point10 = new \stdClass();
        $point11 = new \stdClass();
        $point12 = new \stdClass();
        $point13 = new \stdClass();
        $point14 = new \stdClass();

        $extractor = Mockery::mock('GeoIO\\Extractor');

        $extractor
            ->shouldReceive('extractType')
            ->once()
            ->with($multiPolygon)
            ->andReturn(Extractor::TYPE_MULTIPOLYGON)
        ;

        $extractor
            ->shouldReceive('extractDimension')
            ->once()
            ->with($multiPolygon)
            ->andReturn(Dimension::DIMENSION_2D)
        ;

        $extractor
            ->shouldReceive('extractPolygonsFromMultiPolygon')
            ->once()
            ->with($multiPolygon)
            ->andReturn(array($polygon1, $polygon2, $polygon3))
        ;

        $extractor
            ->shouldReceive('extractLineStringsFromPolygon')
            ->once()
            ->with($polygon1)
            ->andReturn(array($lineString1, $lineString2))
        ;

        $extractor
            ->shouldReceive('extractLineStringsFromPolygon')
            ->once()
            ->with($polygon2)
            ->andReturn(array())
        ;

        $extractor
            ->shouldReceive('extractLineStringsFromPolygon')
            ->once()
            ->with($polygon3)
            ->andReturn(array($lineString3))
        ;

        $extractor
            ->shouldReceive('extractPointsFromLineString')
            ->once()
            ->with($lineString1)
            ->andReturn(array($point1, $point2, $point3, $point4, $point5))
        ;

        $extractor
            ->shouldReceive('extractPointsFromLineString')
            ->once()
            ->with($lineString2)
            ->andReturn(array($point6, $point7, $point8, $point9))
        ;

        $extractor
            ->shouldReceive('extractPointsFromLineString')
            ->once()
            ->with($lineString3)
            ->andReturn(array($point10, $point11, $point12, $point13, $point14))
        ;

        $extractor
            ->shouldReceive('extractCoordinatesFromPoint')
            ->times(14)
            ->andReturn(
                $this->coords(0, 0),
                $this->coords(10, 0),
                $this->coords(10, 10),
                $this->coords(0, 10),
                $this->coords(0, 0),

                $this->coords(1, 1),
                $this->coords(2, 2),
                $this->coords(3, 1),
                $this->coords(1, 1),

                $this->coords(20, 20),
                $this->coords(30, 20),
                $this->coords(30, 30),
                $this->coords(20, 30),
                $this->coords(20, 20)
            )
        ;

        $expected = sprintf('MultiPolygon(((%F %F, %F %F, %F %F, %F %F, %F %F), (%F %F, %F %F, %F %F, %F %F)), EMPTY, ((%F %F, %F %F, %F %F, %F %F, %F %F)))', 0, 0, 10, 0, 10, 10, 0, 10, 0, 0, 1, 1, 2, 2, 3, 1, 1, 1, 20, 20, 30, 20, 30, 30, 20, 30, 20, 20);

        $generator = new Generator($extractor);

        $this->assertSame($expected, $generator->generate($multiPolygon));
    }

    public function testMultiPolygonEmpty()
    {
        $multiPolygon = new \stdClass();

        $extractor = Mockery::mock('GeoIO\\Extractor');

        $extractor
            ->shouldReceive('extractType')
            ->once()
            ->with($multiPolygon)
            ->andReturn(Extractor::TYPE_MULTIPOLYGON)
        ;

        $extractor
            ->shouldReceive('extractDimension')
            ->once()
            ->with($multiPolygon)
            ->andReturn(Dimension::DIMENSION_2D)
        ;

        $extractor
            ->shouldReceive('extractPolygonsFromMultiPolygon')
            ->once()
            ->with($multiPolygon)
            ->andReturn(array())
        ;

        $generator = new Generator($extractor);

        $this->assertSame('MultiPolygon EMPTY', $generator->generate($multiPolygon));
    }

    public function testGeometryCollecton()
    {
        $geometryCollection = new \stdClass();
        $point = new \stdClass();
        $lineString = new \stdClass();
        $point1 = new \stdClass();
        $point2 = new \stdClass();
        $point3 = new \stdClass();

        $extractor = Mockery::mock('GeoIO\\Extractor');

        $extractor
            ->shouldReceive('extractType')
            ->once()
            ->with($geometryCollection)
            ->andReturn(Extractor::TYPE_GEOMETRYCOLLECTION)
        ;

        $extractor
            ->shouldReceive('extractDimension')
            ->once()
            ->with($geometryCollection)
            ->andReturn(Dimension::DIMENSION_2D)
        ;

        $extractor
            ->shouldReceive('extractGeometriesFromGeometryCollection')
            ->once()
            ->with($geometryCollection)
            ->andReturn(array($point, $lineString))
        ;

        $extractor
            ->shouldReceive('extractType')
            ->once()
            ->with($point)
            ->andReturn(Extractor::TYPE_POINT)
        ;

        $extractor
            ->shouldReceive('extractType')
            ->once()
            ->with($lineString)
            ->andReturn(Extractor::TYPE_LINESTRING)
        ;

        $extractor
            ->shouldReceive('extractPointsFromLineString')
            ->once()
            ->with($lineString)
            ->andReturn(array($point1, $point2, $point3))
        ;

        $extractor
            ->shouldReceive('extractCoordinatesFromPoint')
            ->times(4)
            ->andReturn(
                $this->coords(-1, -2),

                $this->coords(1, 2),
                $this->coords(3, 4),
                $this->coords(5, 6)
            )
        ;

        $expected = sprintf('GeometryCollection(Point(%F %F), LineString(%F %F, %F %F, %F %F))', -1, -2, 1, 2, 3, 4, 5, 6);

        $generator = new Generator($extractor);

        $this->assertSame($expected, $generator->generate($geometryCollection));
    }

    public function testGeometryCollectonEmpty()
    {
        $geometryCollection = new \stdClass();

        $extractor = Mockery::mock('GeoIO\\Extractor');

        $extractor
            ->shouldReceive('extractType')
            ->once()
            ->with($geometryCollection)
            ->andReturn(Extractor::TYPE_GEOMETRYCOLLECTION)
        ;

        $extractor
            ->shouldReceive('extractDimension')
            ->once()
            ->with($geometryCollection)
            ->andReturn(Dimension::DIMENSION_2D)
        ;

        $extractor
            ->shouldReceive('extractGeometriesFromGeometryCollection')
            ->once()
            ->with($geometryCollection)
            ->andReturn(array())
        ;

        $generator = new Generator($extractor);

        $this->assertSame('GeometryCollection EMPTY', $generator->generate($geometryCollection));
    }

    public function testConstructorShouldThrowExceptionForInvalidFormatOption()
    {
        $this->setExpectedException('GeoIO\\WKT\Generator\\Exception\\InvalidOptionException');

        new Generator($extractor = Mockery::mock('GeoIO\\Extractor'), array(
            'format' => 'foo'
        ));
    }

    public function testConstructorShouldThrowExceptionForInvalidCaseOption()
    {
        $this->setExpectedException('GeoIO\\WKT\Generator\\Exception\\InvalidOptionException');

        new Generator($extractor = Mockery::mock('GeoIO\\Extractor'), array(
            'case' => 'foo'
        ));
    }

    public function testConstructorShouldAcceptFloatPrecisionOption()
    {
        $point = new \stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_2D,
            $this->coords(1, 2)
        );

        $expected = sprintf('Point(%.15F %.15F)', 1, 2);

        $generator = new Generator($extractor, array(
            'float_precision' => 15
        ));
        $this->assertSame($expected, $generator->generate($point));
    }

    public function testGenerateShouldCatchExtractorExceptions()
    {
        $this->setExpectedException('GeoIO\\WKT\Generator\\Exception\\GeneratorException');

        $extractor = Mockery::mock('GeoIO\\Extractor');

        $extractor->shouldIgnoreMissing();

        $extractor
            ->shouldReceive('extractType')
            ->once()
            ->andThrow(new \Exception())
        ;

        $generator = new Generator($extractor);
        $generator->generate('foo');
    }

    protected function coords($x, $y, $z = null, $m = null)
    {
        return array(
            'x' => $x,
            'y' => $y,
            'z' => $z,
            'm' => $m
        );
    }

    protected function getPointExtractorMock($point, $dimension, $coords, $srid = null)
    {
        $extractor = Mockery::mock('GeoIO\\Extractor');

        $extractor
            ->shouldReceive('extractType')
            ->once()
            ->with($point)
            ->andReturn(Extractor::TYPE_POINT)
        ;

        $extractor
            ->shouldReceive('extractDimension')
            ->once()
            ->with($point)
            ->andReturn($dimension)
        ;

        $extractor
            ->shouldReceive('extractCoordinatesFromPoint')
            ->once()
            ->with($point)
            ->andReturn($coords)
        ;

        if ($srid) {
            $extractor
                ->shouldReceive('extractSrid')
                ->once()
                ->with($point)
                ->andReturn($srid)
            ;
        }

        return $extractor;
    }
}
