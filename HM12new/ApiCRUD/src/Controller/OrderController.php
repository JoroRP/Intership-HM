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

    #[Route('/show', name: 'order_show', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('order/index.html.twig');
    }

    #[Route('/', name: 'order_list', methods: ['GET'])]
    public function listOrders(Request $request): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 5);

        $orderRepository = $this->em->getRepository(Order::class);
        $totalOrders = $orderRepository->count([]);
        $offset = ($page - 1) * $limit;

        $orders = $orderRepository->createQueryBuilder('o')
            ->select('o.id, o.order_date, o.total, o.status, c.name AS customer_name')
            ->join('o.customer', 'c')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getArrayResult();

        return new JsonResponse([
            'orders' => $orders,
            'page' => $page,
            'totalPages' => ceil($totalOrders / $limit),
        ]);
    }

    #[Route('/', name: 'order_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['total']) || empty($data['status']) || empty($data['customer_id'])) {
            return new JsonResponse(['error' => 'All required fields must be filled.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $status = OrderStatus::from($data['status']);
        } catch (\ValueError $e) {
            return new JsonResponse(['error' => 'Invalid status value.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $customer = $this->em->getRepository(Customer::class)->find($data['customer_id']);
        if (!$customer) {
            return new JsonResponse(['error' => 'Customer not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $order = new Order();
        $order->setOrderDate(new \DateTime())
            ->setTotal((float)$data['total'])
            ->setStatus($status)
            ->setCustomer($customer);

        $this->em->persist($order);
        $this->em->flush();

        return new JsonResponse(['success' => 'Order created successfully!', 'order' => $order], JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'order_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $order = $this->em->getRepository(Order::class)->find($id);
        if (!$order) {
            return new JsonResponse(['error' => 'Order not found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        $order->setTotal($data['total'] ?? $order->getTotal())
            ->setStatus(OrderStatus::from($data['status'] ?? $order->getStatus()->value))
            ->setOrderDate(new \DateTime($data['order_date'] ?? $order->getOrderDate()->format('Y-m-d H:i:s')));

        $this->em->flush();

        return new JsonResponse(['success' => 'Order updated successfully!']);
    }

    #[Route('/{id}', name: 'order_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $order = $this->em->getRepository(Order::class)->find($id);
        if (!$order) {
            return new JsonResponse(['error' => 'Order not found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->em->remove($order);
        $this->em->flush();

        return new JsonResponse(['success' => 'Order deleted successfully!']);
    }
}
