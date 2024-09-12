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

    #[Route('', methods: ['GET'])]
    public function listCustomers(): Response
    {
        $customers = $this->em->getRepository(Customer::class)->findAll();
        return $this->json($customers);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function getCustomer(int $id): Response
    {
        $customer = $this->em->getRepository(Customer::class)->find($id);

        if (!$customer) {
            return $this->json(['error' => 'Customer not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($customer);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['error' => 'Invalid JSON'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $allowedFields = ['name', 'email', 'address', 'phone'];
        $unexpectedFields = array_diff(array_keys($data), $allowedFields);
        if (!empty($unexpectedFields)) {
            return new JsonResponse(['error' => 'Unexpected fields: ' . implode(', ', $unexpectedFields)], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!isset($data['name']) || empty($data['name'])) {
            return new JsonResponse(['error' => 'Customer name is required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse(['error' => 'Valid email is required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!isset($data['address']) || empty($data['address'])) {
            return new JsonResponse(['error' => 'Customer address is required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (isset($data['phone']) && !ctype_digit($data['phone'])) {
            return new JsonResponse(['error' => 'Phone number should contain only digits'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $customer = new Customer();
        $customer->setName($data['name']);
        $customer->setEmail($data['email']);
        $customer->setAddress($data['address']);
        $customer->setPhone($data['phone'] ?? null);

        $this->em->persist($customer);
        $this->em->flush();

        return new JsonResponse([
            'status' => 'Customer created',
            'customer_id' => $customer->getId(),
        ], JsonResponse::HTTP_CREATED);
    }


    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $customer = $this->em->getRepository(Customer::class)->find($id);
        if (!$customer) {
            return new JsonResponse(['error' => 'Customer not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['error' => 'Invalid JSON'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $requiredFields = ['name', 'email', 'address', 'phone'];
        $missingFields = array_diff($requiredFields, array_keys($data));

        if (!empty($missingFields)) {
            return new JsonResponse(['error' => 'Missing fields: ' . implode(', ', $missingFields)], JsonResponse::HTTP_BAD_REQUEST);
        }

        $allowedFields = ['name', 'email', 'address', 'phone'];
        $unexpectedFields = array_diff(array_keys($data), $allowedFields);
        if (!empty($unexpectedFields)) {
            return new JsonResponse(['error' => 'Unexpected fields: ' . implode(', ', $unexpectedFields)], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (empty($data['name'])) {
            return new JsonResponse(['error' => 'Customer name is required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse(['error' => 'Valid email is required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (empty($data['address'])) {
            return new JsonResponse(['error' => 'Customer address is required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!ctype_digit($data['phone'])) {
            return new JsonResponse(['error' => 'Phone number should contain only digits'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $customer->setName($data['name']);
        $customer->setEmail($data['email']);
        $customer->setAddress($data['address']);
        $customer->setPhone($data['phone']);

        $this->em->flush();

        return new JsonResponse(['status' => 'Customer updated'], JsonResponse::HTTP_OK);
    }


    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $customer = $this->em->getRepository(Customer::class)->find($id);
        if (!$customer) {
            return new JsonResponse(['error' => 'Customer not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->em->remove($customer);
        $this->em->flush();

        return new JsonResponse(['status' => 'Customer deleted'], JsonResponse::HTTP_OK);
    }
}
