<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Order;
use App\Entity\Customer;
use App\Entity\OrderStatus;
use Doctrine\ORM\EntityManagerInterface;

class OrderControllerTest extends WebTestCase
{
    private $client;
    private $em;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->em = $this->getContainer()->get(EntityManagerInterface::class);

        $this->truncateEntities();
    }

    private function truncateEntities(): void
    {
        $connection = $this->em->getConnection();
        $connection->executeStatement('SET foreign_key_checks = 0');
        $connection->executeStatement('TRUNCATE TABLE orders');
        $connection->executeStatement('TRUNCATE TABLE customers');
        $connection->executeStatement('SET foreign_key_checks = 1');
    }

    public function testListOrders(): void
    {
        $this->client->request('GET', '/api/orders/');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($responseData);
    }

    public function testGetOrder(): void
    {
        $customer = new Customer();
        $customer->setName('John Doe');
        $customer->setEmail('john@example.com');
        $customer->setAddress('123 Main St');
        $this->em->persist($customer);
        $this->em->flush();

        $order = new Order();
        $order->setOrderDate(new \DateTime('2024-09-12'));
        $order->setTotal(99.99);
        $order->setStatus(OrderStatus::Pending);
        $order->setCustomer($customer);
        $this->em->persist($order);
        $this->em->flush();

        $this->client->request('GET', '/api/orders/' . $order->getId());
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals(99.99, $responseData['total']);
        $this->assertEquals('Pending', $responseData['status']);
    }

    public function testGetOrderNotFound(): void
    {
        $this->client->request('GET', '/api/orders/999');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('Order not found', $responseData['error']);
    }

    public function testCreateOrder(): void
    {
        $customer = new Customer();
        $customer->setName('John Doe');
        $customer->setEmail('john@example.com');
        $customer->setAddress('123 Main St');
        $this->em->persist($customer);
        $this->em->flush();

        $data = [
            'total' => 199.99,
            'status' => 'Pending',
            'customer_id' => $customer->getId(),
        ];

        $this->client->request(
            'POST',
            '/api/orders',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('Order created successfully!', $responseData['message']);

        $order = $this->em->getRepository(Order::class)->findOneBy(['total' => 199.99]);
        $this->assertNotNull($order);
    }

    public function testCreateOrderInvalidJson(): void
    {
        $this->client->request(
            'POST',
            '/api/orders',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            'invalid json'
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('Invalid JSON format', $responseData['error']);
    }

    public function testUpdateOrder(): void
    {
        $customer = new Customer();
        $customer->setName('John Doe');
        $customer->setEmail('john@example.com');
        $customer->setAddress('123 Main St');
        $this->em->persist($customer);
        $this->em->flush();

        $order = new Order();
        $order->setOrderDate(new \DateTime('2024-09-12'));
        $order->setTotal(99.99);
        $order->setStatus(OrderStatus::Pending);
        $order->setCustomer($customer);
        $this->em->persist($order);
        $this->em->flush();

        $data = [
            'order_date' => '2024-09-13',
            'total' => 149.99,
            'status' => 'Completed',
            'customer_id' => $customer->getId(),
        ];

        $this->client->request(
            'PUT',
            '/api/orders/' . $order->getId(),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('Order updated successfully!', $responseData['status']);

        $updatedOrder = $this->em->getRepository(Order::class)->find($order->getId());
        $this->assertEquals(149.99, $updatedOrder->getTotal());
        $this->assertEquals(OrderStatus::Completed, $updatedOrder->getStatus());
    }

    public function testDeleteOrder(): void
    {
        $customer = new Customer();
        $customer->setName('John Doe');
        $customer->setEmail('john@example.com');
        $customer->setAddress('123 Main St');
        $this->em->persist($customer);
        $this->em->flush();

        $order = new Order();
        $order->setOrderDate(new \DateTime('2024-09-12'));
        $order->setTotal(99.99);
        $order->setStatus(OrderStatus::Pending);
        $order->setCustomer($customer);
        $this->em->persist($order);
        $this->em->flush();

        $this->assertNotNull($order->getId(), 'Order ID should not be null after flush.');
        $orderId = $order->getId();
        echo "DEBUG: Order ID is: $orderId\n";

        $this->client->request('DELETE', '/api/orders/' . $orderId);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('Order deleted', $responseData['status']);

        $deletedOrder = $this->em->getRepository(Order::class)->find($orderId);
        $this->assertNull($deletedOrder, 'Order should be deleted from the database.');
    }


    public function tearDown(): void
    {
        parent::tearDown();
        $this->truncateEntities();
    }
}
