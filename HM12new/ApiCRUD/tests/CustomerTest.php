<?php

namespace App\Tests\Entity;

use App\Entity\Customer;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    public function testGetId()
    {
        $customer = new Customer();
        $this->assertNull($customer->getId());
    }

    public function testGetName()
    {
        $customer = new Customer();
        $customer->setName('John Doe');
        $this->assertSame('John Doe', $customer->getName());
    }

    public function testSetName()
    {
        $customer = new Customer();
        $customer->setName('Jane Doe');
        $this->assertEquals('Jane Doe', $customer->getName());
    }

    public function testGetEmail()
    {
        $customer = new Customer();
        $customer->setEmail('johndoe@example.com');
        $this->assertSame('johndoe@example.com', $customer->getEmail());
    }

    public function testSetEmail()
    {
        $customer = new Customer();
        $customer->setEmail('janedoe@example.com');
        $this->assertEquals('janedoe@example.com', $customer->getEmail());
    }

    public function testGetAddress()
    {
        $customer = new Customer();
        $customer->setAddress('123 Main St');
        $this->assertSame('123 Main St', $customer->getAddress());
    }

    public function testSetAddress()
    {
        $customer = new Customer();
        $customer->setAddress('456 Elm St');
        $this->assertEquals('456 Elm St', $customer->getAddress());
    }

    public function testGetPhone()
    {
        $customer = new Customer();
        $customer->setPhone('08876543210');
        $this->assertSame('08876543210', $customer->getPhone());
    }

    public function testSetPhone()
    {
        $customer = new Customer();
        $customer->setPhone('08876543210');
        $this->assertEquals('08876543210', $customer->getPhone());
    }
}
