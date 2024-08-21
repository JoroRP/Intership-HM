<?php
declare(strict_types=1);
namespace GeorgiRadoslavov\Hm3new\Tests;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Calculator.php';

use function GeorgiRadoslavov\Hm3new\calculate;
use PHPUnit\Framework\TestCase;



final class CalculatorTest extends TestCase
{

    public function testAddition(): void
    {
        //include_once '../src/calculator.php'; - DISCLAIMER - works as well in place of require_once
        $result = calculate("plus", 5, 3);
        $this->assertEquals(8, $result);
    }


    public function testSubtraction(): void
    {
        $result = calculate("minus", 5, 3);
        $this->assertEquals(2, $result);
    }


    public function testMultiplication(): void
    {
        $result = calculate("multiply", 5, 3);
        $this->assertEquals(15, $result);
    }


    public function testDivision(): void
    {
        $result = calculate("divide", 6, 3);
        $this->assertEquals(2, $result);
    }


    public function testDivisionByZero(): void
    {
        $result = calculate("divide", 6, 0);
        $this->assertEquals("Error: Division by zero is not allowed.", $result);
    }


    public function testModulo(): void
    {
        $result = calculate("modulo", 5, 3);
        $this->assertEquals(2, $result);
    }

    public function testModuloByZero(): void
    {
        $result = calculate("modulo", 5, 0);
        $this->assertEquals("Error: Modulo by zero is not allowed.", $result);
    }

    public function testExponentiation(): void
    {
        $result = calculate("exponentiation", 2, 3);
        $this->assertEquals(8, $result);
    }

    public function testInvalidOperator(): void
    {
        $result = calculate("invalid", 5, 3);
        $this->assertEquals("Error: Invalid operator provided.", $result);
    }
}
