<?php

namespace App\Tests\Entity;

use App\Entity\Order;
use App\Entity\Customer;
use App\Entity\OrderStatus;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    public function testOrderDate()
    {
        $order = new Order();
        $orderDate = new \DateTime('2024-09-23');

        $order->setOrderDate($orderDate);

        $this->assertSame($orderDate, $order->getOrderDate());
    }

    public function testTotal()
    {
        $order = new Order();
        $order->setTotal(100.50);

        $this->assertSame(100.50, $order->getTotal());
    }

    public function testStatus()
    {
        $order = new Order();
        $status = OrderStatus::Pending;

        $order->setStatus($status);

        $this->assertSame($status, $order->getStatus());
    }

    public function testCustomer()
    {
        $order = new Order();
        $customer = new Customer();
        $customer->setName('John Doe');

        $order->setCustomer($customer);

        $this->assertSame($customer, $order->getCustomer());
        $this->assertEquals('John Doe', $order->getCustomer()->getName());
    }

    public function testOrderId()
    {
        $order = new Order();

        $this->assertNull($order->getId());
    }
}
