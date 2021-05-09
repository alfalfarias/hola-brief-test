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
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $orders = $entityManager->getRepository(Order::class)->findBy([],[
            'id' => 'DESC'
        ]);

        $response = [];

        foreach ($orders as $order) {
            $order_response = [
                'id' => $order->getid(),
                'price' => $order->getPrice(),
                'discount' => $order->getDiscount(),
                'total' => $order->getTotal(),
                'products' => [],
                'coupon' => null,
            ];

            $products = $order->getProducts();
            foreach ($products as $product) {
                $product_response =  [
                    'code' => $product->getCode(),
                    'price' => $product->getPrice(),
                ];
                $order_response['products'][] = $product_response;
            }

            $coupon = $order->getCoupon();
            if ($coupon) {
                $coupon_response =  [
                    'id' => $coupon->getId(),
                    'code' => $coupon->getCode(),
                    'type' => $coupon->getType(),
                    'value' => $coupon->getValue(),
                    'rules' => [],
                ];

                $rules = $coupon->getRules();
                foreach ($rules as $rule) {
                    $rule_response = [
                        'id' => $rule->getId(),
                        'type' => $rule->getType(),
                        'value' => $rule->getValue(),
                    ];
                    $coupon_response['rules'][] = $rule_response;
                }

                $order_response['coupon'] = $coupon_response;
            }

            $response[] = $order_response;
        }

        return $this->json($response, 200);
    }

    /**
     * @Route("/orders", methods={"POST"}, name="order_create")
     */
    public function create(Request $request, OrderService $orderService): Response
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

        $response = [
            'price' => $order->getPrice(),
            'discount' => $order->getDiscount(),
            'total' => $order->getTotal(),
        ];
        return $this->json($response, 201);
    }
}
