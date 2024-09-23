<?php

namespace App\Tests\Entity;

use App\Entity\Category;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    public function testGetId()
    {
        $category = new Category();

        $this->assertNull($category->getId());
    }

    public function testGetName()
    {
        $category = new Category();
        $category->setName('Electronics');

        $this->assertSame('Electronics', $category->getName());
    }

    public function testSetName()
    {
        $category = new Category();
        $category->setName('TVs');

        $this->assertEquals('TVs', $category->getName());
    }

    public function testGetDescription()
    {
        $category = new Category();
        $category->setDescription('A category for electronic items');

        $this->assertSame('A category for electronic items', $category->getDescription());
    }

    public function testSetDescription()
    {
        $category = new Category();
        $category->setDescription('Tvs category description');

        $this->assertEquals('Tvs category description', $category->getDescription());
    }
}
