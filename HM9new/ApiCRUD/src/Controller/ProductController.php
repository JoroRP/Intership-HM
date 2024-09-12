<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[\Symfony\Component\Routing\Annotation\Route('/api/products')]
class ProductController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/', methods: ['GET'])]
    public function listProducts(): Response
    {
        $products = $this->em->getRepository(Product::class)->findAll();
        return $this->json($products);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function getProduct(int $id): Response
    {
        $product = $this->em->getRepository(Product::class)->find($id);

        if (!$product) {
            return $this->json(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($product);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['error' => 'Invalid JSON'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $allowedFields = ['name', 'price', 'quantity', 'description', 'category'];

        $unexpectedFields = array_diff(array_keys($data), $allowedFields);
        if (!empty($unexpectedFields)) {
            return new JsonResponse(['error' => 'Unexpected fields: ' . implode(', ', $unexpectedFields)], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!isset($data['name']) || empty($data['name'])) {
            return new JsonResponse(['error' => 'Product name is required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!isset($data['price']) || !is_numeric($data['price'])) {
            return new JsonResponse(['error' => 'Price must be a number'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!isset($data['quantity']) || !is_int($data['quantity'])) {
            return new JsonResponse(['error' => 'Quantity must be an integer'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!isset($data['category']) || !is_array($data['category']) || empty($data['category'])) {
            return new JsonResponse(['error' => 'At least one category is required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $product = new Product();
        $product->setName($data['name']);
        $product->setPrice((float)$data['price']);
        $product->setQuantity((int)$data['quantity']);
        $product->setDescription($data['description'] ?? '');

        foreach ($data['category'] as $categoryId) {
            $category = $this->em->getRepository(Category::class)->find($categoryId);
            if (!$category) {
                return new JsonResponse(['error' => "Category with ID $categoryId not found"], JsonResponse::HTTP_BAD_REQUEST);
            }
            $product->addCategory($category);
        }

        $this->em->persist($product);
        $this->em->flush();

        return new JsonResponse([
            'status' => 'Product created',
            'product_id' => $product->getId()
        ], JsonResponse::HTTP_CREATED);
    }


    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $product = $this->em->getRepository(Product::class)->find($id);
        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['error' => 'Invalid JSON'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $requiredFields = ['name', 'price', 'quantity', 'categories'];
        $missingFields = array_diff($requiredFields, array_keys($data));

        if (!empty($missingFields)) {
            return new JsonResponse(['error' => 'Missing fields: ' . implode(', ', $missingFields)], JsonResponse::HTTP_BAD_REQUEST);
        }

        $allowedFields = ['name', 'price', 'quantity', 'description', 'categories'];
        $unexpectedFields = array_diff(array_keys($data), $allowedFields);
        if (!empty($unexpectedFields)) {
            return new JsonResponse(['error' => 'Unexpected fields: ' . implode(', ', $unexpectedFields)], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (empty($data['name'])) {
            return new JsonResponse(['error' => 'Product name is required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!is_numeric($data['price'])) {
            return new JsonResponse(['error' => 'Price must be a number'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!is_int($data['quantity'])) {
            return new JsonResponse(['error' => 'Quantity must be an integer'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!is_array($data['categories']) || empty($data['categories'])) {
            return new JsonResponse(['error' => 'At least one category is required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $product->setName($data['name']);
        $product->setPrice((float)$data['price']);
        $product->setQuantity((int)$data['quantity']);
        $product->setDescription($data['description'] ?? '');

        $product->getCategory()->clear();
        foreach ($data['categories'] as $categoryId) {
            $category = $this->em->getRepository(Category::class)->find($categoryId);
            if (!$category) {
                return new JsonResponse(['error' => "Category with ID $categoryId not found"], JsonResponse::HTTP_BAD_REQUEST);
            }
            $product->addCategory($category);
        }

        $this->em->flush();

        return new JsonResponse(['status' => 'Product updated'], JsonResponse::HTTP_OK);
    }


    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $product = $this->em->getRepository(Product::class)->find($id);
        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->em->remove($product);
        $this->em->flush();

        return new JsonResponse(['status' => 'Product deleted'], JsonResponse::HTTP_OK);
    }
}
