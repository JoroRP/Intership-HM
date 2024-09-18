<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Genre;
use App\Entity\Redactor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $genres = [];
        for ($i = 0; $i < 10; $i++) {
            $genre = new Genre();
            $genre->setName($faker->word())
                ->setDescription($faker->sentence());
            $manager->persist($genre);
            $genres[] = $genre;
        }

        $authors = [];
        for ($i = 0; $i < 5; $i++) {
            $author = new Author();
            $author->setName($faker->name())
                ->setBirthYear($faker->numberBetween(1900, 2000))
                ->setNationality($faker->country());
            $manager->persist($author);
            $authors[] = $author;
        }

        $redactors = [];
        for ($i = 0; $i < 5; $i++) {
            $redactor = new Redactor();
            $redactor->setName($faker->name())
                ->setUniqueID($faker->unique()->randomNumber(5))
                ->setSpecialty($faker->word());
            $manager->persist($redactor);
            $redactors[] = $redactor;
        }

        for ($i = 0; $i < 15; $i++) {
            $book = new Book();
            $book->setTitle($faker->sentence(3))
                ->setIsbn($faker->isbn13())
                ->setPublicationYear($faker->numberBetween(1950, 2023))
                ->setAuthor($faker->randomElement($authors));

            $randomGenres = $faker->randomElements($genres, rand(1, 3));
            foreach ($randomGenres as $genre) {
                $book->addGenre($genre);
            }

            $randomRedactors = $faker->randomElements($redactors, rand(1, 2));
            foreach ($randomRedactors as $redactor) {
                $redactor->addBook($book);
            }

            $manager->persist($book);
        }

        $manager->flush();
    }
}
