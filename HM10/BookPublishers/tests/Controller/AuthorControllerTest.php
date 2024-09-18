<?php

namespace App\Tests\Controller;

use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class AuthorControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/author/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Author::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Select Author - Book Publishing');

        self::assertSelectorExists('select#author');
    }

    public function testNew(): void
    {
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Add Author', [
            'author[name]' => 'Test Author',
            'author[birthYear]' => 1985,
            'author[nationality]' => 'Test Nationality',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testEdit(): void
    {
        $fixture = new Author();
        $fixture->setName('Old Author');
        $fixture->setBirthYear(1970);
        $fixture->setNationality('Old Nationality');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save Changes', [
            'author[name]' => 'Updated Author',
            'author[birthYear]' => 1980,
            'author[nationality]' => 'Updated Nationality',
        ]);

        self::assertResponseRedirects($this->path);

        $fixture = $this->repository->find($fixture->getId());
        self::assertSame('Updated Author', $fixture->getName());
        self::assertSame(1980, $fixture->getBirthYear());
        self::assertSame('Updated Nationality', $fixture->getNationality());
    }


}
