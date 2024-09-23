<?php

namespace App\Controller;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/customers')]
class CustomerController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/show', name: 'customer_show', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('customer/index.html.twig');
    }

    #[Route('/', name: 'customer_list', methods: ['GET'])]
    public function listCustomers(Request $request): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 5);

        $customerRepository = $this->em->getRepository(Customer::class);
        $totalCustomers = $customerRepository->count([]);
        $offset = ($page - 1) * $limit;

        $customers = $customerRepository->createQueryBuilder('c')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getArrayResult();

        return new JsonResponse([
            'customers' => $customers,
            'page' => $page,
            'totalPages' => ceil($totalCustomers / $limit),
        ]);
    }

    #[Route('/', name: 'customer_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['name']) || empty($data['email']) || empty($data['address'])) {
            return new JsonResponse(['error' => 'All required fields must be filled.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $customer = new Customer();
        $customer->setName($data['name'])
            ->setEmail($data['email'])
            ->setAddress($data['address'])
            ->setPhone($data['phone'] ?? null);

        $this->em->persist($customer);
        $this->em->flush();

        return new JsonResponse(['success' => 'Customer created successfully!', 'customer' => $customer], JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'customer_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $customer = $this->em->getRepository(Customer::class)->find($id);
        if (!$customer) {
            return new JsonResponse(['error' => 'Customer not found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        $customer->setName($data['name'] ?? $customer->getName())
            ->setEmail($data['email'] ?? $customer->getEmail())
            ->setAddress($data['address'] ?? $customer->getAddress())
            ->setPhone($data['phone'] ?? $customer->getPhone());

        $this->em->flush();

        return new JsonResponse(['success' => 'Customer updated successfully!']);
    }

    #[Route('/{id}', name: 'customer_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $customer = $this->em->getRepository(Customer::class)->find($id);
        if (!$customer) {
            return new JsonResponse(['error' => 'Customer not found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->em->remove($customer);
        $this->em->flush();

        return new JsonResponse(['success' => 'Customer deleted successfully!']);
    }
}
