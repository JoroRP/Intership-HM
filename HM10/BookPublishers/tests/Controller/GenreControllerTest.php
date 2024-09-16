<?php

namespace App\Tests\Controller;

use App\Entity\Genre;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class GenreControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/genre/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Genre::class);

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
        self::assertPageTitleContains('Genres - Book Publishing');
        self::assertSelectorExists('select#genre');
    }

    public function testNew(): void
    {
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Create Genre', [
            'genre[name]' => 'Test Genre',
            'genre[description]' => 'A test genre description',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testEdit(): void
    {
        $fixture = new Genre();
        $fixture->setName('Old Genre');
        $fixture->setDescription('Old Description');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save Changes', [
            'genre[name]' => 'Updated Genre',
            'genre[description]' => 'Updated Description',
        ]);

        self::assertResponseRedirects($this->path);

        $fixture = $this->repository->find($fixture->getId());
        self::assertSame('Updated Genre', $fixture->getName());
        self::assertSame('Updated Description', $fixture->getDescription());
    }
    
}
