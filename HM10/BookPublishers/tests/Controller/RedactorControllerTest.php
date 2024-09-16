<?php

namespace App\Tests\Controller;

use App\Entity\Redactor;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class RedactorControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/redactor/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Redactor::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Redactors - Book Publishing');

        self::assertSelectorExists('select#redactor');
    }

    public function testNew(): void
    {
        $this->client->request('GET', sprintf('%snew', $this->path));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Create Redactor', [
            'redactor[name]' => 'Test Redactor',
            'redactor[uniqueID]' => '1234',
            'redactor[specialty]' => 'Editing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testEdit(): void
    {
        $fixture = new Redactor();
        $fixture->setName('Original Redactor');
        $fixture->setUniqueID('5678');
        $fixture->setSpecialty('Proofreading');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));
        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save Changes', [
            'redactor[name]' => 'Updated Redactor',
            'redactor[uniqueID]' => '9101',
            'redactor[specialty]' => 'Copyediting',
        ]);

        self::assertResponseRedirects($this->path);

        $updatedRedactor = $this->repository->find($fixture->getId());
        self::assertSame('Updated Redactor', $updatedRedactor->getName());
        self::assertSame(9101, $updatedRedactor->getUniqueID());
        self::assertSame('Copyediting', $updatedRedactor->getSpecialty());
    }


}
