<?php

namespace App\Controller\Api;

use App\Entity\Coupon;
use App\Entity\Order;
use App\Entity\Product;
use App\Service\Order as OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
* @Route("/api/order")
*/
class OrderController extends AbstractController
{
    const HTTP_ERROR = [
        'COUPON_NOT_FOUND' => 'COUPON_NOT_FOUND',
    ];

    /**
     * @Route("/orders", methods={"GET"}, name="order_index")
     */
    public function index(SerializerInterface $serializer): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $orders = $entityManager->getRepository(Order::class)->findBy([],[
            'id' => 'DESC'
        ]);

        $response = [];
        foreach ($orders as $order) {
            $order_response = $serializer->normalize($order, null, [
                AbstractNormalizer::ATTRIBUTES => [
                    'id', 
                    'price', 
                    'discount',
                    'total',
                    'products' => [
                        'code',
                        'price',
                    ],
                    'coupon' => [
                        'id',
                        'code',
                        'type',
                        'value',
                        'rules' => [
                            'id',
                            'type',
                            'value',
                        ],
                    ],
                ]
            ]);

            $response[] = $order_response;
        }
        return $this->json($response, 200);
    }

    /**
     * @Route("/orders", methods={"POST"}, name="order_create")
     */
    public function create(Request $request, SerializerInterface $serializer, OrderService $orderService): Response
    {
        $request_body = json_decode($request->getContent(), true);
        $coupon_data = $request_body['coupon'];
        $products_data = $request_body['products'];

        $entityManager = $this->getDoctrine()->getManager();

        $coupon = null;
        if ($coupon_data) {
            $coupon = $entityManager->getRepository(Coupon::class)->findOneBy([
                'code' => $coupon_data['code'],
            ]);
            if (!$coupon) {
                return $this->json(OrderController::HTTP_ERROR['COUPON_NOT_FOUND'], 422);
            }
        }

        $product_codes_data = [];
        foreach ($products_data as $product_data) {
            $product_codes_data[] = $product_data['code'];
        }

        $products = $entityManager->getRepository(Product::class)->findBy([
            'code' => $product_codes_data,
        ]);

        $order = $orderService->create($products, $coupon);

        $response = $serializer->normalize($order, null, [
            AbstractNormalizer::ATTRIBUTES => [
                'price', 
                'discount',
                'total',
            ]
        ]);
        return $this->json($response, 201);
    }
}
