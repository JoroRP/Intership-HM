<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Product;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class ProductControllerTest extends WebTestCase
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
        $connection->executeStatement('TRUNCATE TABLE product_category');
        $connection->executeStatement('TRUNCATE TABLE products');
        $connection->executeStatement('TRUNCATE TABLE categories');
        $connection->executeStatement('SET foreign_key_checks = 1');
    }

    public function testListProducts(): void
    {
        $this->client->followRedirects();
        $this->client->request('GET', '/api/products');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($responseData);
    }


    public function testGetProduct(): void
    {
        $category = new Category();
        $category->setName('Test Category');
        $this->em->persist($category);
        $this->em->flush();

        $product = new Product();
        $product->setName('Test Product');
        $product->setPrice(100.0);
        $product->setQuantity(10);
        $product->setDescription('Test Description');
        $product->addCategory($category);
        $this->em->persist($product);
        $this->em->flush();

        $this->client->request('GET', '/api/products/' . $product->getId());
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('Test Product', $responseData['name']);
        $this->assertEquals('Test Description', $responseData['description']);
        $this->assertEquals(100.0, $responseData['price']);
        $this->assertEquals(10, $responseData['quantity']);
    }

    public function testGetProductNotFound(): void
    {
        $this->client->request('GET', '/api/products/999');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('Product not found', $responseData['error']);
    }

    public function testCreateProduct(): void
    {
        $category = new Category();
        $category->setName('Test Category');
        $this->em->persist($category);
        $this->em->flush();

        $data = [
            'name' => 'New Product',
            'price' => 49.99,
            'quantity' => 50,
            'description' => 'A new product',
            'category' => [$category->getId()]
        ];

        $this->client->request(
            'POST',
            '/api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('Product created', $responseData['status']);

        $product = $this->em->getRepository(Product::class)->findOneBy(['name' => 'New Product']);
        $this->assertNotNull($product);
    }

    public function testCreateProductInvalidJson(): void
    {
        $this->client->request(
            'POST',
            '/api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            'invalid json'
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('Invalid JSON', $responseData['error']);
    }

    public function testUpdateProduct(): void
    {
        $category = new Category();
        $category->setName('Old Category');
        $this->em->persist($category);
        $this->em->flush();

        $product = new Product();
        $product->setName('Old Product');
        $product->setPrice(100.0);
        $product->setQuantity(10);
        $product->setDescription('Old Description');
        $product->addCategory($category);
        $this->em->persist($product);
        $this->em->flush();

        $data = [
            'name' => 'Updated Product',
            'price' => 199.99,
            'quantity' => 20,
            'description' => 'Updated Description',
            'categories' => [$category->getId()]
        ];

        $this->client->request(
            'PUT',
            '/api/products/' . $product->getId(),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('Product updated', $responseData['status']);

        $updatedProduct = $this->em->getRepository(Product::class)->find($product->getId());
        $this->assertEquals('Updated Product', $updatedProduct->getName());
        $this->assertEquals(199.99, $updatedProduct->getPrice());
    }

    public function testDeleteProduct(): void
    {
        $category = new Category();
        $category->setName('Delete Category');
        $this->em->persist($category);
        $this->em->flush();

        $product = new Product();
        $product->setName('Delete Product');
        $product->setPrice(100.0);
        $product->setQuantity(10);
        $product->setDescription('Delete Description');
        $product->addCategory($category);

        $this->em->persist($product);
        $this->em->flush();

        $this->assertNotNull($product->getId(), 'Product ID should not be null after flush.');

        $this->em->clear();

        $this->client->request('DELETE', '/api/products/' . $product->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('Product deleted', $responseData['status']);

        $this->em->clear();

        $deletedProduct = $this->em->getRepository(Product::class)->find($product->getId());
        $this->assertNull($deletedProduct, 'Product should be deleted from the database.');
    }


    public function tearDown(): void
    {
        parent::tearDown();
        $this->truncateEntities();
    }
}
