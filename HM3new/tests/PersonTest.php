<?php

declare(strict_types=1);
namespace GeorgiRadoslavov\Hm3new\Tests;

require __DIR__ . '/../vendor/autoload.php';

use GeorgiRadoslavov\Hm3new\Person;
use PHPUnit\Framework\TestCase;

final class PersonTest extends TestCase
{


    public function testAge(): void
    {
        $p = new Person(10, "Ivan");
        $age = $p->getAge();
        $this->assertSame(10, $age);
    }
}