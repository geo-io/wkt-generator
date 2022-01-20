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
use PHPUnit\Framework\TestCase;
use stdClass;

class GeneratorTest extends TestCase
{
    public function testPoint(): void
    {
        $point = new stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_2D,
            $this->coords(1, 2),
        );

        $expected = sprintf('Point(%F %F)', 1, 2);

        $generator = new Generator($extractor);
        $this->assertSame($expected, $generator->generate($point));
    }

    public function testPointEmpty(): void
    {
        $point = new stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_2D,
            null,
        );

        $generator = new Generator($extractor);
        $this->assertSame('Point EMPTY', $generator->generate($point));
    }

    public function testPointZ(): void
    {
        $point = new stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_3DZ,
            $this->coords(1, 2, 3),
        );

        $expected = sprintf('Point(%F %F %F)', 1, 2, 3);

        $generator = new Generator($extractor);
        $this->assertSame($expected, $generator->generate($point));
    }

    public function testPointM(): void
    {
        $point = new stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_3DM,
            $this->coords(1, 2, null, 3),
        );

        $expected = sprintf('Point(%F %F %F)', 1, 2, 3);

        $generator = new Generator($extractor);
        $this->assertSame($expected, $generator->generate($point));
    }

    public function testPointZM(): void
    {
        $point = new stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_4D,
            $this->coords(1, 2, 3, 4),
        );

        $expected = sprintf('Point(%F %F %F %F)', 1, 2, 3, 4);

        $generator = new Generator($extractor);
        $this->assertSame($expected, $generator->generate($point));
    }

    public function testPointUpperCase(): void
    {
        $point = new stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_2D,
            $this->coords(1, 2),
        );

        $expected = sprintf('POINT(%F %F)', 1, 2);

        $generator = new Generator($extractor, [
            'case' => Generator::CASE_UPPER,
        ]);

        $this->assertSame($expected, $generator->generate($point));
    }

    public function testPointLowerCase(): void
    {
        $point = new stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_2D,
            $this->coords(1, 2),
        );

        $expected = sprintf('point(%F %F)', 1, 2);

        $generator = new Generator($extractor, [
            'case' => Generator::CASE_LOWER,
        ]);

        $this->assertSame($expected, $generator->generate($point));
    }

    public function testPointWkt11Strict(): void
    {
        $point = new stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_4D,
            $this->coords(1, 2, 3, 4),
        );

        $expected = sprintf('Point(%F %F)', 1, 2);

        $generator = new Generator($extractor, [
            'format' => Generator::FORMAT_WKT11_STRICT,
        ]);

        $this->assertSame($expected, $generator->generate($point));
    }

    public function testPointWkt12(): void
    {
        $point = new stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_2D,
            $this->coords(1, 2),
        );

        $expected = sprintf('Point(%F %F)', 1, 2);

        $generator = new Generator($extractor, [
            'format' => Generator::FORMAT_WKT12,
        ]);

        $this->assertSame($expected, $generator->generate($point));
    }

    public function testPointWkt12Empty(): void
    {
        $point = new stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_2D,
            null,
        );

        $generator = new Generator($extractor, [
            'format' => Generator::FORMAT_WKT12,
        ]);

        $this->assertSame('Point EMPTY', $generator->generate($point));
    }

    public function testPointWkt12Z(): void
    {
        $point = new stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_3DZ,
            $this->coords(1, 2, 3),
        );

        $expected = sprintf('Point Z (%F %F %F)', 1, 2, 3);

        $generator = new Generator($extractor, [
            'format' => Generator::FORMAT_WKT12,
        ]);
        $this->assertSame($expected, $generator->generate($point));
    }

    public function testPointWkt12M(): void
    {
        $point = new stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_3DM,
            $this->coords(1, 2, null, 3),
        );

        $expected = sprintf('Point M (%F %F %F)', 1, 2, 3);

        $generator = new Generator($extractor, [
            'format' => Generator::FORMAT_WKT12,
        ]);
        $this->assertSame($expected, $generator->generate($point));
    }

    public function testPointWkt12ZM(): void
    {
        $point = new stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_4D,
            $this->coords(1, 2, 3, 4),
        );

        $expected = sprintf('Point ZM (%F %F %F %F)', 1, 2, 3, 4);

        $generator = new Generator($extractor, [
            'format' => Generator::FORMAT_WKT12,
        ]);
        $this->assertSame($expected, $generator->generate($point));
    }

    public function testPointEwkt(): void
    {
        $point = new stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_2D,
            $this->coords(1, 2),
        );

        $expected = sprintf('Point(%F %F)', 1, 2);

        $generator = new Generator($extractor, [
            'format' => Generator::FORMAT_EWKT,
        ]);

        $this->assertSame($expected, $generator->generate($point));
    }

    public function testPointEwktEmpty(): void
    {
        $point = new stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_2D,
            null,
        );

        $generator = new Generator($extractor, [
            'format' => Generator::FORMAT_EWKT,
        ]);

        $this->assertSame('Point EMPTY', $generator->generate($point));
    }

    public function testPointEwktZ(): void
    {
        $point = new stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_3DZ,
            $this->coords(1, 2, 3),
        );

        $expected = sprintf('Point(%F %F %F)', 1, 2, 3);

        $generator = new Generator($extractor, [
            'format' => Generator::FORMAT_EWKT,
        ]);
        $this->assertSame($expected, $generator->generate($point));
    }

    public function testPointEwktM(): void
    {
        $point = new stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_3DM,
            $this->coords(1, 2, null, 3),
        );

        $expected = sprintf('PointM(%F %F %F)', 1, 2, 3);

        $generator = new Generator($extractor, [
            'format' => Generator::FORMAT_EWKT,
        ]);
        $this->assertSame($expected, $generator->generate($point));
    }

    public function testPointEwktZM(): void
    {
        $point = new stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_4D,
            $this->coords(1, 2, 3, 4),
        );

        $expected = sprintf('Point(%F %F %F %F)', 1, 2, 3, 4);

        $generator = new Generator($extractor, [
            'format' => Generator::FORMAT_EWKT,
        ]);
        $this->assertSame($expected, $generator->generate($point));
    }

    public function testPointEwktWithSrid(): void
    {
        $point = new stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_2D,
            $this->coords(1, 2),
            1234,
        );

        $expected = sprintf('SRID=1234;Point(%F %F)', 1, 2);

        $generator = new Generator($extractor, [
            'format' => Generator::FORMAT_EWKT,
            'emit_srid' => true,
        ]);

        $this->assertSame($expected, $generator->generate($point));
    }

    public function testLineString(): void
    {
        $lineString = new stdClass();
        $point1 = new stdClass();
        $point2 = new stdClass();
        $point3 = new stdClass();

        $extractor = $this->createMock(Extractor::class);

        $extractor
            ->expects($this->once())
            ->method('extractType')
            ->with($lineString)
            ->willReturn(GeometryType::LINESTRING)
        ;

        $extractor
            ->expects($this->once())
            ->method('extractDimension')
            ->with($lineString)
            ->willReturn(Dimension::DIMENSION_2D)
        ;

        $extractor
            ->expects($this->once())
            ->method('extractPointsFromLineString')
            ->with($lineString)
            ->willReturn([$point1, $point2, $point3])
        ;

        $extractor
            ->expects($this->exactly(3))
            ->method('extractCoordinatesFromPoint')
            ->with($lineString)
            ->willReturnOnConsecutiveCalls(
                $this->coords(1, 2),
                $this->coords(3, 4),
                $this->coords(5, 6),
            )
        ;

        $expected = sprintf(
            'LineString(%F %F, %F %F, %F %F)',
            1, 2, 3, 4, 5, 6,
        );

        $generator = new Generator($extractor);

        $this->assertSame($expected, $generator->generate($lineString));
    }

    public function testLineStringEmpty(): void
    {
        $lineString = new stdClass();

        $extractor = $this->createMock(Extractor::class);

        $extractor
            ->expects($this->once())
            ->method('extractType')
            ->with($lineString)
            ->willReturn(GeometryType::LINESTRING)
        ;

        $extractor
            ->expects($this->once())
            ->method('extractDimension')
            ->with($lineString)
            ->willReturn(Dimension::DIMENSION_2D)
        ;

        $extractor
            ->expects($this->once())
            ->method('extractPointsFromLineString')
            ->with($lineString)
            ->willReturn([])
        ;

        $generator = new Generator($extractor);

        $this->assertSame(
            'LineString EMPTY',
            $generator->generate($lineString),
        );
    }

    public function testPolygon(): void
    {
        $polygon = new stdClass();
        $lineString = new stdClass();
        $point1 = new stdClass();
        $point2 = new stdClass();
        $point3 = new stdClass();
        $point4 = new stdClass();

        $extractor = $this->createMock(Extractor::class);

        $extractor
            ->expects($this->once())
            ->method('extractType')
            ->with($polygon)
            ->willReturn(GeometryType::POLYGON)
        ;

        $extractor
            ->expects($this->once())
            ->method('extractDimension')
            ->with($polygon)
            ->willReturn(Dimension::DIMENSION_2D)
        ;

        $extractor
            ->expects($this->once())
            ->method('extractLineStringsFromPolygon')
            ->with($polygon)
            ->willReturn([$lineString])
        ;

        $extractor
            ->expects($this->once())
            ->method('extractPointsFromLineString')
            ->with($lineString)
            ->willReturn([$point1, $point2, $point3, $point4])
        ;

        $extractor
            ->expects($this->exactly(4))
            ->method('extractCoordinatesFromPoint')
            ->willReturnOnConsecutiveCalls(
                $this->coords(1, 2),
                $this->coords(3, 4),
                $this->coords(6, 5),
                $this->coords(1, 2),
            )
        ;

        $expected = sprintf(
            'Polygon((%F %F, %F %F, %F %F, %F %F))',
            1, 2, 3, 4, 6, 5, 1, 2,
        );

        $generator = new Generator($extractor);

        $this->assertSame($expected, $generator->generate($polygon));
    }

    public function testPolygonWithHole(): void
    {
        $polygon = new stdClass();
        $lineString1 = new stdClass();
        $lineString2 = new stdClass();
        $point1 = new stdClass();
        $point2 = new stdClass();
        $point3 = new stdClass();
        $point4 = new stdClass();
        $point5 = new stdClass();
        $point6 = new stdClass();
        $point7 = new stdClass();
        $point8 = new stdClass();
        $point9 = new stdClass();

        $extractor = $this->createMock(Extractor::class);

        $extractor
            ->expects($this->once())
            ->method('extractType')
            ->with($polygon)
            ->willReturn(GeometryType::POLYGON)
        ;

        $extractor
            ->expects($this->once())
            ->method('extractDimension')
            ->with($polygon)
            ->willReturn(Dimension::DIMENSION_2D)
        ;

        $extractor
            ->expects($this->once())
            ->method('extractLineStringsFromPolygon')
            ->with($polygon)
            ->willReturn([$lineString1, $lineString2])
        ;

        $extractor
            ->expects($this->exactly(2))
            ->method('extractPointsFromLineString')
            ->withConsecutive([$lineString1], [$lineString2])
            ->willReturnOnConsecutiveCalls(
                [$point1, $point2, $point3, $point4, $point5],
                [$point6, $point7, $point8, $point9],
            )
        ;

        $extractor
            ->expects($this->exactly(9))
            ->method('extractCoordinatesFromPoint')
            ->willReturnOnConsecutiveCalls(
                $this->coords(0, 0),
                $this->coords(10, 0),
                $this->coords(10, 10),
                $this->coords(0, 10),
                $this->coords(0, 0),

                $this->coords(1, 1),
                $this->coords(2, 2),
                $this->coords(3, 1),
                $this->coords(1, 1),
            )
        ;

        $expected = sprintf(
            'Polygon((%F %F, %F %F, %F %F, %F %F, %F %F), (%F %F, %F %F, %F %F, %F %F))',
            0, 0, 10, 0, 10, 10, 0, 10, 0, 0,
            1, 1, 2, 2, 3, 1, 1, 1,
        );

        $generator = new Generator($extractor);

        $this->assertSame($expected, $generator->generate($polygon));
    }

    public function testPolygonEmpty(): void
    {
        $polygon = new stdClass();

        $extractor = $this->createMock(Extractor::class);

        $extractor
            ->expects($this->once())
            ->method('extractType')
            ->with($polygon)
            ->willReturn(GeometryType::POLYGON)
        ;

        $extractor
            ->expects($this->once())
            ->method('extractDimension')
            ->with($polygon)
            ->willReturn(Dimension::DIMENSION_2D)
        ;

        $extractor
            ->expects($this->once())
            ->method('extractLineStringsFromPolygon')
            ->with($polygon)
            ->willReturn([])
        ;

        $generator = new Generator($extractor);

        $this->assertSame('Polygon EMPTY', $generator->generate($polygon));
    }

    public function testMultiPoint(): void
    {
        $multiPoint = new stdClass();
        $point1 = new stdClass();
        $point2 = new stdClass();
        $point3 = new stdClass();

        $extractor = $this->createMock(Extractor::class);

        $extractor
            ->expects($this->once())
            ->method('extractType')
            ->with($multiPoint)
            ->willReturn(GeometryType::MULTIPOINT)
        ;

        $extractor
            ->expects($this->once())
            ->method('extractDimension')
            ->with($multiPoint)
            ->willReturn(Dimension::DIMENSION_2D)
        ;

        $extractor
            ->expects($this->once())
            ->method('extractPointsFromMultiPoint')
            ->with($multiPoint)
            ->willReturn([$point1, $point2])
        ;

        $extractor
            ->expects($this->exactly(2))
            ->method('extractCoordinatesFromPoint')
            ->willReturnOnConsecutiveCalls(
                $this->coords(1, 2),
                $this->coords(3, 4),
            )
        ;

        $expected = sprintf('MultiPoint((%F %F), (%F %F))', 1, 2, 3, 4);

        $generator = new Generator($extractor);

        $this->assertSame($expected, $generator->generate($multiPoint));
    }

    public function testMultiPointEmpty(): void
    {
        $multiPoint = new stdClass();

        $extractor = $this->createMock(Extractor::class);

        $extractor
            ->expects($this->once())
            ->method('extractType')
            ->with($multiPoint)
            ->willReturn(GeometryType::MULTIPOINT)
        ;

        $extractor
            ->expects($this->once())
            ->method('extractDimension')
            ->with($multiPoint)
            ->willReturn(Dimension::DIMENSION_3DZ)
        ;

        $extractor
            ->expects($this->once())
            ->method('extractPointsFromMultiPoint')
            ->with($multiPoint)
            ->willReturn([])
        ;

        $generator = new Generator($extractor);

        $this->assertSame('MultiPoint EMPTY', $generator->generate($multiPoint));
    }

    public function testMultiLineString(): void
    {
        $multiLineString = new stdClass();
        $lineString1 = new stdClass();
        $lineString2 = new stdClass();
        $lineString3 = new stdClass();
        $point1 = new stdClass();
        $point2 = new stdClass();
        $point3 = new stdClass();
        $point4 = new stdClass();
        $point5 = new stdClass();

        $extractor = $this->createMock(Extractor::class);

        $extractor
            ->expects($this->once())
            ->method('extractType')
            ->with($multiLineString)
            ->willReturn(GeometryType::MULTILINESTRING)
        ;

        $extractor
            ->expects($this->once())
            ->method('extractDimension')
            ->with($multiLineString)
            ->willReturn(Dimension::DIMENSION_2D)
        ;

        $extractor
            ->expects($this->once())
            ->method('extractLineStringsFromMultiLineString')
            ->with($multiLineString)
            ->willReturn([$lineString1, $lineString2, $lineString3])
        ;

        $extractor
            ->expects($this->exactly(3))
            ->method('extractPointsFromLineString')
            ->withConsecutive([$lineString1], [$lineString2])
            ->willReturnOnConsecutiveCalls(
                [$point1, $point2, $point3],
                [$point4, $point5],
                [],
            )
        ;

        $extractor
            ->expects($this->exactly(5))
            ->method('extractCoordinatesFromPoint')
            ->willReturnOnConsecutiveCalls(
                $this->coords(1, 2),
                $this->coords(3, 4),
                $this->coords(5, 6),

                $this->coords(-1, -2),
                $this->coords(-3, -4),
            )
        ;

        $expected = sprintf('MultiLineString((%F %F, %F %F, %F %F), (%F %F, %F %F), EMPTY)', 1, 2, 3, 4, 5, 6, -1, -2, -3, -4);

        $generator = new Generator($extractor);

        $this->assertSame($expected, $generator->generate($multiLineString));
    }

    public function testMultiLineStringEmpty(): void
    {
        $multiLineString = new stdClass();

        $extractor = $this->createMock(Extractor::class);

        $extractor
            ->expects($this->once())
            ->method('extractType')
            ->with($multiLineString)
            ->willReturn(GeometryType::MULTILINESTRING)
        ;

        $extractor
            ->expects($this->once())
            ->method('extractDimension')
            ->with($multiLineString)
            ->willReturn(Dimension::DIMENSION_2D)
        ;

        $extractor
            ->expects($this->once())
            ->method('extractLineStringsFromMultiLineString')
            ->with($multiLineString)
            ->willReturn([])
        ;

        $generator = new Generator($extractor);

        $this->assertSame('MultiLineString EMPTY', $generator->generate($multiLineString));
    }

    public function testMultiPolygon(): void
    {
        $multiPolygon = new stdClass();
        $polygon1 = new stdClass();
        $polygon2 = new stdClass();
        $polygon3 = new stdClass();
        $lineString1 = new stdClass();
        $lineString2 = new stdClass();
        $lineString3 = new stdClass();
        $point1 = new stdClass();
        $point2 = new stdClass();
        $point3 = new stdClass();
        $point4 = new stdClass();
        $point5 = new stdClass();
        $point6 = new stdClass();
        $point7 = new stdClass();
        $point8 = new stdClass();
        $point9 = new stdClass();
        $point10 = new stdClass();
        $point11 = new stdClass();
        $point12 = new stdClass();
        $point13 = new stdClass();
        $point14 = new stdClass();

        $extractor = $this->createMock(Extractor::class);

        $extractor
            ->expects($this->once())
            ->method('extractType')
            ->with($multiPolygon)
            ->willReturn(GeometryType::MULTIPOLYGON)
        ;

        $extractor
            ->expects($this->once())
            ->method('extractDimension')
            ->with($multiPolygon)
            ->willReturn(Dimension::DIMENSION_2D)
        ;

        $extractor
            ->expects($this->once())
            ->method('extractPolygonsFromMultiPolygon')
            ->with($multiPolygon)
            ->willReturn([$polygon1, $polygon2, $polygon3])
        ;

        $extractor
            ->expects($this->exactly(3))
            ->method('extractLineStringsFromPolygon')
            ->withConsecutive([$polygon1], [$polygon2], [$polygon3])
            ->willReturnOnConsecutiveCalls(
                [$lineString1, $lineString2],
                [],
                [$lineString3],
            )
        ;

        $extractor
            ->expects($this->exactly(3))
            ->method('extractPointsFromLineString')
            ->withConsecutive([$lineString1], [$lineString2], [$lineString3])
            ->willReturnOnConsecutiveCalls(
                [$point1, $point2, $point3, $point4, $point5],
                [$point6, $point7, $point8, $point9],
                [$point10, $point11, $point12, $point13, $point14],
            )
        ;

        $extractor
            ->expects($this->exactly(14))
            ->method('extractCoordinatesFromPoint')
            ->willReturnOnConsecutiveCalls(
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
                $this->coords(20, 20),
            )
        ;

        $expected = sprintf(
            'MultiPolygon(((%F %F, %F %F, %F %F, %F %F, %F %F), (%F %F, %F %F, %F %F, %F %F)), EMPTY, ((%F %F, %F %F, %F %F, %F %F, %F %F)))',
            0, 0, 10, 0, 10, 10, 0, 10, 0, 0,
            1, 1, 2, 2, 3, 1, 1, 1,
            20, 20, 30, 20, 30, 30, 20, 30, 20, 20,
        );

        $generator = new Generator($extractor);

        $this->assertSame($expected, $generator->generate($multiPolygon));
    }

    public function testMultiPolygonEmpty(): void
    {
        $multiPolygon = new stdClass();

        $extractor = $this->createMock(Extractor::class);

        $extractor
            ->expects($this->once())
            ->method('extractType')
            ->with($multiPolygon)
            ->willReturn(GeometryType::MULTIPOLYGON)
        ;

        $extractor
            ->expects($this->once())
            ->method('extractDimension')
            ->with($multiPolygon)
            ->willReturn(Dimension::DIMENSION_2D)
        ;

        $extractor
            ->expects($this->once())
            ->method('extractPolygonsFromMultiPolygon')
            ->with($multiPolygon)
            ->willReturn([])
        ;

        $generator = new Generator($extractor);

        $this->assertSame(
            'MultiPolygon EMPTY',
            $generator->generate($multiPolygon),
        );
    }

    public function testGeometryCollection(): void
    {
        $geometryCollection = new stdClass();
        $point = new stdClass();
        $lineString = new stdClass();
        $point1 = new stdClass();
        $point2 = new stdClass();
        $point3 = new stdClass();

        $extractor = $this->createMock(Extractor::class);

        $extractor
            ->expects($this->exactly(3))
            ->method('extractType')
            ->withConsecutive(
                [$geometryCollection],
                [$point],
                [$lineString],
            )
            ->willReturnOnConsecutiveCalls(
                GeometryType::GEOMETRYCOLLECTION,
                GeometryType::POINT,
                GeometryType::LINESTRING,
            )
        ;

        $extractor
            ->expects($this->once())
            ->method('extractDimension')
            ->with($geometryCollection)
            ->willReturn(Dimension::DIMENSION_2D)
        ;

        $extractor
            ->expects($this->once())
            ->method('extractGeometriesFromGeometryCollection')
            ->with($geometryCollection)
            ->willReturn([$point, $lineString])
        ;

        $extractor
            ->expects($this->once())
            ->method('extractPointsFromLineString')
            ->with($lineString)
            ->willReturn([$point1, $point2, $point3])
        ;

        $extractor
            ->expects($this->exactly(4))
            ->method('extractCoordinatesFromPoint')
            ->willReturnOnConsecutiveCalls(
                $this->coords(-1, -2),

                $this->coords(1, 2),
                $this->coords(3, 4),
                $this->coords(5, 6),
            )
        ;

        $expected = sprintf(
            'GeometryCollection(Point(%F %F), LineString(%F %F, %F %F, %F %F))',
            -1, -2,
            1, 2, 3, 4, 5, 6,
        );

        $generator = new Generator($extractor);

        $this->assertSame($expected, $generator->generate($geometryCollection));
    }

    public function testGeometryCollectonEmpty(): void
    {
        $geometryCollection = new stdClass();

        $extractor = $this->createMock(Extractor::class);

        $extractor
            ->expects($this->once())
            ->method('extractType')
            ->with($geometryCollection)
            ->willReturn(GeometryType::GEOMETRYCOLLECTION)
        ;

        $extractor
            ->expects($this->once())
            ->method('extractDimension')
            ->with($geometryCollection)
            ->willReturn(Dimension::DIMENSION_2D)
        ;

        $extractor
            ->expects($this->once())
            ->method('extractGeometriesFromGeometryCollection')
            ->with($geometryCollection)
            ->willReturn([])
        ;

        $generator = new Generator($extractor);

        $this->assertSame(
            'GeometryCollection EMPTY',
            $generator->generate($geometryCollection),
        );
    }

    public function testConstructorShouldThrowExceptionForInvalidFormatOption(): void
    {
        $this->expectException(InvalidOptionException::class);

        new Generator($this->createMock(Extractor::class), [
            'format' => 'foo',
        ]);
    }

    public function testConstructorShouldThrowExceptionForInvalidCaseOption()
    {
        $this->expectException(InvalidOptionException::class);

        new Generator($this->createMock(Extractor::class), [
            'case' => 'foo',
        ]);
    }

    public function testConstructorShouldAcceptFloatPrecisionOption(): void
    {
        $point = new stdClass();

        $extractor = $this->getPointExtractorMock(
            $point,
            Dimension::DIMENSION_2D,
            $this->coords(1, 2),
        );

        $expected = sprintf('Point(%.15F %.15F)', 1, 2);

        $generator = new Generator($extractor, [
            'float_precision' => 15,
        ]);
        $this->assertSame($expected, $generator->generate($point));
    }

    public function testGenerateShouldCatchExtractorExceptions()
    {
        $this->expectException(GeneratorException::class);

        $extractor = $this->createMock(Extractor::class);

        $extractor
            ->expects($this->once())
            ->method('extractDimension')
            ->willThrowException(new Exception())
        ;

        $generator = new Generator($extractor);
        $generator->generate(new stdClass());
    }

    private function coords(
        float $x,
        float $y,
        ?float $z = null,
        ?float $m = null,
    ): Coordinates {
        return new Coordinates(
            x: $x,
            y: $y,
            z: $z,
            m: $m,
        );
    }

    private function getPointExtractorMock(
        object $point,
        Dimension $dimension,
        ?Coordinates $coords,
        int $srid = null,
    ): Extractor {
        $extractor = $this->createMock(Extractor::class);

        $extractor
            ->expects($this->once())
            ->method('extractType')
            ->with($point)
            ->willReturn(GeometryType::POINT)
        ;

        $extractor
            ->expects($this->once())
            ->method('extractDimension')
            ->with($point)
            ->willReturn($dimension)
        ;

        $extractor
            ->expects($this->once())
            ->method('extractCoordinatesFromPoint')
            ->with($point)
            ->willReturn($coords)
        ;

        if ($srid) {
            $extractor
                ->expects($this->once())
                ->method('extractSrid')
                ->with($point)
                ->willReturn($srid)
            ;
        }

        return $extractor;
    }
}
