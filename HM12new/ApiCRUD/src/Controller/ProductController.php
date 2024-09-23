<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/products')]
class ProductController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/show', name: 'product_show', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('product/index.html.twig');
    }

    #[Route('/category', name: 'category_product_list', methods: ['GET'])]
    public function listCategories(): JsonResponse
    {
        $categories = $this->em->getRepository(Category::class)->findAll();

        $categoryData = [];
        foreach ($categories as $category) {
            $categoryData[] = [
                'id' => $category->getId(),
                'name' => $category->getName(),
            ];
        }

        return new JsonResponse($categoryData);
    }

    #[Route('/', name: 'product_list', methods: ['GET'])]
    public function listProducts(Request $request): JsonResponse
    {
        $page = max(1, $request->query->getInt('page', 1));
        $limit = $request->query->getInt('limit', 5);

        $productRepository = $this->em->getRepository(Product::class);
        $totalProducts = $productRepository->count([]);
        $offset = ($page - 1) * $limit;

        $products = $productRepository->findBy([], null, $limit, $offset);

        $productData = [];

        foreach ($products as $product) {
            $productData[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'quantity' => $product->getQuantity(),
                'description' => $product->getDescription(),
                'categories' => array_map(function ($category) {
                    return [
                        'id' => $category->getId(),
                        'name' => $category->getName(),
                    ];
                }, $product->getCategory()->toArray())
            ];
        }

        $totalProducts = $productRepository->count([]);

        return new JsonResponse([
            'products' => $productData,
            'page' => $page,
            'totalPages' => ceil($totalProducts / $limit),
        ]);
    }

    #[Route('/', name: 'product_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['name']) || empty($data['price']) || empty($data['quantity']) || empty($data['categories'])) {
            return new JsonResponse(['error' => 'All required fields must be filled.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $product = new Product();
        $product->setName($data['name'])
            ->setPrice((float)$data['price'])
            ->setQuantity((int)$data['quantity'])
            ->setDescription($data['description'] ?? '');

        foreach ($data['categories'] as $categoryId) {
            $category = $this->em->getRepository(Category::class)->find($categoryId);
            if (!$category) {
                return new JsonResponse(['error' => "Category with ID $categoryId not found"], JsonResponse::HTTP_BAD_REQUEST);
            }
            $product->addCategory($category);
        }

        $this->em->persist($product);
        $this->em->flush();

        return new JsonResponse(['success' => 'Product created successfully!', 'product' => $product], JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'product_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $product = $this->em->getRepository(Product::class)->find($id);
        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        $product->setName($data['name'] ?? $product->getName())
            ->setPrice($data['price'] ?? $product->getPrice())
            ->setQuantity($data['quantity'] ?? $product->getQuantity())
            ->setDescription($data['description'] ?? $product->getDescription());

        $this->em->flush();

        return new JsonResponse(['success' => 'Product updated successfully!']);
    }

    #[Route('/{id}', name: 'product_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $product = $this->em->getRepository(Product::class)->find($id);
        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->em->remove($product);
        $this->em->flush();

        return new JsonResponse(['success' => 'Product deleted successfully!']);
    }
}
