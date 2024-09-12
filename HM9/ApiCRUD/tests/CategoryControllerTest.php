<?php

namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class CategoryControllerTest extends WebTestCase
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
        $connection->executeStatement('TRUNCATE TABLE categories');
        $connection->executeStatement('SET foreign_key_checks = 1');
    }

    public function testListCategories(): void
    {
        $this->client->request('GET', '/api/categories');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($responseData);
    }

    public function testGetCategory(): void
    {
        $category = new Category();
        $category->setName('Test Category');
        $category->setDescription('Test Description');
        $this->em->persist($category);
        $this->em->flush();

        $this->client->request('GET', '/api/categories/' . $category->getId());
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('Test Category', $responseData['name']);
        $this->assertEquals('Test Description', $responseData['description']);
    }

    public function testGetCategoryNotFound(): void
    {
        $this->client->request('GET', '/api/categories/999');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('Category not found', $responseData['error']);
    }

    public function testCreateCategory(): void
    {
        $data = [
            'name' => 'New Category',
            'description' => 'New Description',
        ];

        $this->client->request(
            'POST',
            '/api/categories',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('Category created', $responseData['status']);

        $category = $this->em->getRepository(Category::class)->findOneBy(['name' => 'New Category']);
        $this->assertNotNull($category);
    }

    public function testCreateCategoryInvalidJson(): void
    {
        $this->client->request(
            'POST',
            '/api/categories',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            'invalid json'
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);


        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('Invalid JSON', $responseData['error']);
    }

    public function testUpdateCategory(): void
    {
        $category = new Category();
        $category->setName('Old Category');
        $category->setDescription('Old Description');
        $this->em->persist($category);
        $this->em->flush();

        $data = [
            'name' => 'Updated Category',
            'description' => 'Updated Description',
        ];

        $this->client->request(
            'PUT',
            '/api/categories/' . $category->getId(),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('Category updated', $responseData['status']);

        $updatedCategory = $this->em->getRepository(Category::class)->find($category->getId());
        $this->assertEquals('Updated Category', $updatedCategory->getName());
        $this->assertEquals('Updated Description', $updatedCategory->getDescription());
    }

    public function testDeleteCategory(): void
    {
        $category = new Category();
        $category->setName('Delete Category');
        $category->setDescription('Delete Description');
        $this->em->persist($category);
        $this->em->flush();

        $this->assertNotNull($category->getId(), 'Category ID should not be null after flush.');

        $this->client->request('DELETE', '/api/categories/' . $category->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('Category deleted', $responseData['status']);

        $deletedCategory = $this->em->getRepository(Category::class)->findOneBy(['id' => $category->getId()]);
        $this->assertNull($deletedCategory, 'Category should be deleted from the database.');
    }


    public function tearDown(): void
    {
        parent::tearDown();
        $this->truncateEntities();
    }
}
