<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;

class CustomerControllerTest extends WebTestCase
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
        $connection->executeStatement('TRUNCATE TABLE customers');
        $connection->executeStatement('SET foreign_key_checks = 1');
    }

    public function testListCustomers(): void
    {
        $this->client->request('GET', '/api/customers');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($responseData);
    }

    public function testGetCustomer(): void
    {
        $customer = new Customer();
        $customer->setName('Test Customer');
        $customer->setEmail('test@example.com');
        $customer->setAddress('Test Address');
        $customer->setPhone('123456789');
        $this->em->persist($customer);
        $this->em->flush();

        $this->client->request('GET', '/api/customers/' . $customer->getId());
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('Test Customer', $responseData['name']);
        $this->assertEquals('test@example.com', $responseData['email']);
        $this->assertEquals('Test Address', $responseData['address']);
        $this->assertEquals('123456789', $responseData['phone']);
    }

    public function testGetCustomerNotFound(): void
    {
        $this->client->request('GET', '/api/customers/999');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('Customer not found', $responseData['error']);
    }

    public function testCreateCustomer(): void
    {
        $data = [
            'name' => 'New Customer',
            'email' => 'newcustomer@example.com',
            'address' => 'New Address',
            'phone' => '123456789'
        ];

        $this->client->request(
            'POST',
            '/api/customers',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('Customer created', $responseData['status']);

        $customer = $this->em->getRepository(Customer::class)->findOneBy(['email' => 'newcustomer@example.com']);
        $this->assertNotNull($customer);
    }

    public function testCreateCustomerInvalidJson(): void
    {
        $this->client->request(
            'POST',
            '/api/customers',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            'invalid json'
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('Invalid JSON', $responseData['error']);
    }

    public function testUpdateCustomer(): void
    {
        $customer = new Customer();
        $customer->setName('Old Customer');
        $customer->setEmail('old@example.com');
        $customer->setAddress('Old Address');
        $customer->setPhone('987654321');
        $this->em->persist($customer);
        $this->em->flush();

        $data = [
            'name' => 'Updated Customer',
            'email' => 'updated@example.com',
            'address' => 'Updated Address',
            'phone' => '123456789'
        ];

        $this->client->request(
            'PUT',
            '/api/customers/' . $customer->getId(),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('Customer updated', $responseData['status']);

        $updatedCustomer = $this->em->getRepository(Customer::class)->find($customer->getId());
        $this->assertEquals('Updated Customer', $updatedCustomer->getName());
        $this->assertEquals('updated@example.com', $updatedCustomer->getEmail());
    }

    public function testDeleteCustomer(): void
    {
        $customer = new Customer();
        $customer->setName('Delete Customer');
        $customer->setEmail('delete@example.com');
        $customer->setAddress('Delete Address');
        $customer->setPhone('987654321');
        $this->em->persist($customer);
        $this->em->flush();

        $this->assertNotNull($customer->getId(), 'Customer ID should not be null after flush.');

        $this->client->request('DELETE', '/api/customers/' . $customer->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('Customer deleted', $responseData['status']);

        $deletedCustomer = $this->em->getRepository(Customer::class)->findOneBy(['id' => $customer->getId()]);
        $this->assertNull($deletedCustomer, 'Customer should be deleted from the database.');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->truncateEntities();
    }
}
