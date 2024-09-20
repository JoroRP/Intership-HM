<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/posts')]
class PostController extends AbstractController
{
    #[Route('/', name: 'app_post')]
    public function index(): Response
    {
        return $this->render('post/posts.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }
}
