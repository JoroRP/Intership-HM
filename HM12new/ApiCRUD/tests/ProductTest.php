<?php

namespace App\Tests\Entity;


use App\Entity\Category;
use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testGetSetName(): void
    {
        $product = new Product();
        $product->setName('Test Product');

        $this->assertSame('Test Product', $product->getName());
    }

    public function testGetSetPrice(): void
    {
        $product = new Product();
        $product->setPrice(99.99);

        $this->assertSame(99.99, $product->getPrice());
    }

    public function testGetSetQuantity(): void
    {
        $product = new Product();
        $product->setQuantity(10);

        $this->assertSame(10, $product->getQuantity());
    }

    public function testGetSetDescription(): void
    {
        $product = new Product();
        $product->setDescription('This is a sample description');

        $this->assertSame('This is a sample description', $product->getDescription());

        $product->setDescription(null);
        $this->assertNull($product->getDescription());
    }

    public function testAddRemoveCategory(): void
    {
        $product = new Product();
        $category1 = new Category();
        $category1->setName('Category 1');

        $category2 = new Category();
        $category2->setName('Category 2');

        $product->addCategory($category1);
        $product->addCategory($category2);

        $this->assertCount(2, $product->getCategory());
        $this->assertTrue($product->getCategory()->contains($category1));
        $this->assertTrue($product->getCategory()->contains($category2));

        $product->removeCategory($category1);

        $this->assertCount(1, $product->getCategory());
        $this->assertFalse($product->getCategory()->contains($category1));
        $this->assertTrue($product->getCategory()->contains($category2));
    }

    public function testEmptyCategoriesInitially(): void
    {
        $product = new Product();

        $this->assertCount(0, $product->getCategory());
    }

    public function testGetId(): void
    {
        $product = new Product();

        $this->assertNull($product->getId());
    }
}
