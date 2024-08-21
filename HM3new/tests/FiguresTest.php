<?php
declare(strict_types=1);

namespace GeorgiRadoslavov\Hm3new\Tests;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/figures.php';

use function GeorgiRadoslavov\Hm3new\calculateFigure;
use PHPUnit\Framework\TestCase;

final class FiguresTest extends TestCase
{
    public function testTriangleArea(): void
    {
        $result = calculateFigure( "area","triangle", 10, 3, 4, 5);
        $this->assertEquals(25.00, $result);
    }

    public function testTrianglePerimeter(): void
    {
        $result = calculateFigure("perimeter","triangle",10, 3, 4, 5);
        $this->assertEquals(12.00, $result);
    }

    public function testTriangleMissingSides(): void
    {
        $result = calculateFigure( "area","triangle", 10);
        $this->assertEquals("Error: Missing values for sides a, b, c", $result);
    }

    public function testSquareArea(): void
    {
        $result = calculateFigure( "area","square", 5);
        $this->assertEquals(25.00, $result);
    }

    public function testSquarePerimeter(): void
    {
        $result = calculateFigure( "perimeter","square", 5);
        $this->assertEquals(20.00, $result);
    }

    public function testCircleArea(): void
    {
        $result = calculateFigure( "area","circle", 7);
        $this->assertEquals(153.94, $result);
    }

    public function testCirclePerimeter(): void
    {
        $result = calculateFigure( "perimeter","circle", 7);
        $this->assertEquals(43.98, $result);
    }

    public function testInvalidFigure(): void
    {
        $result = calculateFigure( "area","hexagon", 5);
        $this->assertEquals("Error: Invalid figure provided.", $result);
    }

    public function testInvalidCalculation(): void
    {
        $result = calculateFigure( "volume","circle", 7);
        $this->assertEquals("Error: Invalid calculation type provided.", $result);
    }
}
