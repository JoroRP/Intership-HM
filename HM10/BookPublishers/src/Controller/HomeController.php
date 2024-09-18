<?php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/index', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $bookRepository = $entityManager->getRepository(Book::class);
        $books = $bookRepository->findBy([], null, 10);
        shuffle($books);

        $randomBooks = array_slice($books, 0, 3);

        return $this->render('home/homepage.html.twig', [
            'books' => $randomBooks,
        ]);
    }
}
