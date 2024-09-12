<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/categories')]
class CategoryController extends AbstractController
{
    private $em;

    /**
     * @param $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('', methods: ['GET'])]
    public function listCategories(): Response
    {
        $categories = $this->em->getRepository(Category::class)->findAll();
        return $this->json($categories);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function getCategory(int $id): Response
    {
        $category = $this->em->getRepository(Category::class)->find($id);

        if (!$category) {
            return $this->json(['error' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($category);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['error' => 'Invalid JSON'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $allowedFields = ['name', 'description'];

        $unexpectedFields = array_diff(array_keys($data), $allowedFields);
        if (!empty($unexpectedFields)) {
            return new JsonResponse(['error' => 'Unexpected fields: ' . implode(', ', $unexpectedFields)], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!isset($data['name']) || empty($data['name'])) {
            return new JsonResponse(['error' => 'Category name is required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (isset($data['description']) && !is_string($data['description'])) {
            return new JsonResponse(['error' => 'Description must be a string'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $category = new Category();
        $category->setName($data['name']);
        $category->setDescription($data['description'] ?? '');

        $this->em->persist($category);
        $this->em->flush();

        return new JsonResponse(['status' => 'Category created'], JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $category = $this->em->getRepository(Category::class)->find($id);
        if (!$category) {
            return new JsonResponse(['error' => 'Category not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['error' => 'Invalid JSON'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $allowedFields = ['name', 'description'];

        $unexpectedFields = array_diff(array_keys($data), $allowedFields);
        if (!empty($unexpectedFields)) {
            return new JsonResponse(['error' => 'Unexpected fields: ' . implode(', ', $unexpectedFields)], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!isset($data['name']) || empty($data['name'])) {
            return new JsonResponse(['error' => 'Category name is required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!isset($data['description']) || empty($data['description'])) {
            return new JsonResponse(['error' => 'Category description is required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!is_string($data['description'])) {
            return new JsonResponse(['error' => 'Description must be a string'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $category->setName($data['name']);
        $category->setDescription($data['description']);

        $this->em->flush();
        
        return new JsonResponse(['status' => 'Category updated'], JsonResponse::HTTP_OK);
    }


    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $category = $this->em->getRepository(Category::class)->find($id);
        if (!$category) {
            return new JsonResponse(['error' => 'Category not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->em->remove($category);
        $this->em->flush();

        return new JsonResponse(['status' => 'Category deleted'], JsonResponse::HTTP_OK);
    }


}
