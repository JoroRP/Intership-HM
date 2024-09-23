<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/categories')]
class CategoryController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/show', name: 'category_show')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig');
    }

    #[Route('/', name: 'category_list', methods: ['GET'])]
    public function listCategories(Request $request): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 5);

        $categoryRepository = $this->em->getRepository(Category::class);
        $totalCategories = $categoryRepository->count([]);
        $offset = ($page - 1) * $limit;

        $categories = $categoryRepository->createQueryBuilder('c')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getArrayResult();

        return new JsonResponse([
            'categories' => $categories,
            'page' => $page,
            'totalPages' => ceil($totalCategories / $limit),
        ]);
    }

    #[Route('/', name: 'category_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $name = $data['name'] ?? null;
        $description = $data['description'] ?? '';

        if (empty($name)) {
            return new JsonResponse(['error' => 'Category name is required.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $category = new Category();
        $category->setName($name);
        $category->setDescription($description);

        $this->em->persist($category);
        $this->em->flush();

        return new JsonResponse(['success' => 'Category created successfully!', 'category' => $category], JsonResponse::HTTP_CREATED);
    }


    #[Route('/{id}', name: 'category_update', methods: ['PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        $category = $this->em->getRepository(Category::class)->find($id);

        if (!$category) {
            return new JsonResponse(['error' => 'Category not found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $name = $data['name'] ?? null;
        $description = $data['description'] ?? '';

        if (empty($name)) {
            return new JsonResponse(['error' => 'Category name is required.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $category->setName($name);
        $category->setDescription($description);

        $this->em->flush();

        return new JsonResponse(['success' => 'Category updated successfully!', 'category' => $category]);
    }

    #[Route('/{id}', name: 'category_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $category = $this->em->getRepository(Category::class)->find($id);

        if (!$category) {
            return new JsonResponse(['error' => 'Category not found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->em->remove($category);
        $this->em->flush();

        return new JsonResponse(['success' => 'Category deleted successfully!']);
    }
}
