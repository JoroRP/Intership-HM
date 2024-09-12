<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Customer;
use App\Entity\OrderStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/orders')]
class OrderController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/', methods: ['GET'])]
    public function listOrders(): Response
    {
        $orders = $this->em->getRepository(Order::class)->findAll();
        return $this->json($orders);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function getOrder(int $id): Response
    {
        $order = $this->em->getRepository(Order::class)->find($id);

        if (!$order) {
            return $this->json(['error' => 'Order not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($order);
    }

    #[Route('', name: 'create_order', methods: ['POST'])]
    public function createOrder(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->json(['error' => 'Invalid JSON format'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $requiredFields = ['total', 'status', 'customer_id'];
        $missingFields = array_diff($requiredFields, array_keys($data));

        if (!empty($missingFields)) {
            return $this->json(['error' => 'Missing required fields: ' . implode(', ', $missingFields)], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!isset($data['total']) || !is_numeric($data['total'])) {
            return $this->json(['error' => 'Total must be a valid number'], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $status = OrderStatus::from($data['status']);
        } catch (\ValueError $e) {
            return $this->json(['error' => 'Invalid status value'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $customer = $this->em->getRepository(Customer::class)->find($data['customer_id']);
        if (!$customer) {
            return $this->json(['error' => 'Customer not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $order = new Order();
        $order->setOrderDate(new \DateTime());
        $order->setTotal((float)$data['total']);
        $order->setStatus($status);
        $order->setCustomer($customer);

        $this->em->persist($order);
        $this->em->flush();

        return $this->json([
            'message' => 'Order created successfully!',
            'order' => $order
        ], JsonResponse::HTTP_CREATED);
    }


    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $order = $this->em->getRepository(Order::class)->find($id);
        if (!$order) {
            return new JsonResponse(['error' => 'Order not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->json(['error' => 'Invalid JSON format'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $requiredFields = ['order_date', 'total', 'status', 'customer_id'];
        $missingFields = array_diff($requiredFields, array_keys($data));

        if (!empty($missingFields)) {
            return new JsonResponse(['error' => 'Missing required fields: ' . implode(', ', $missingFields)], JsonResponse::HTTP_BAD_REQUEST);
        }

        $allowedFields = ['order_date', 'total', 'status', 'customer_id'];
        $unexpectedFields = array_diff(array_keys($data), $allowedFields);
        if (!empty($unexpectedFields)) {
            return new JsonResponse(['error' => 'Unexpected fields: ' . implode(', ', $unexpectedFields)], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!isset($data['total']) || !is_numeric($data['total'])) {
            return $this->json(['error' => 'Total must be a valid number'], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $status = OrderStatus::from($data['status']);
        } catch (\ValueError $e) {
            return $this->json(['error' => 'Invalid status value'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $customer = $this->em->getRepository(Customer::class)->find($data['customer_id']);
        if (!$customer) {
            return new JsonResponse(['error' => "Customer with ID {$data['customer_id']} not found"], JsonResponse::HTTP_NOT_FOUND);
        }

        try {
            $orderDate = new \DateTime($data['order_date']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Invalid date format for order_date'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $order->setOrderDate($orderDate);
        $order->setTotal((float)$data['total']);
        $order->setStatus($status);
        $order->setCustomer($customer);

        $this->em->flush();

        return new JsonResponse(['status' => 'Order updated successfully!'], JsonResponse::HTTP_OK);
    }


    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $order = $this->em->getRepository(Order::class)->find($id);
        if (!$order) {
            return new JsonResponse(['error' => 'Order not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->em->remove($order);
        $this->em->flush();

        return new JsonResponse(['status' => 'Order deleted'], JsonResponse::HTTP_OK);
    }
}
