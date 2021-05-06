<?php

namespace App\Controller\Api;

use App\Entity\Coupon;
use App\Entity\Order;
use App\Entity\OrderCoupon;
use App\Entity\OrderCouponRule;
use App\Entity\OrderProduct;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/api/order")
*/
class OrderController extends AbstractController
{
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
    public function create(Request $request): Response
    {
        $request_body = json_decode($request->getContent(), true);
        $coupon_data = $request_body['coupon'];
        $products_data = $request_body['products'];

        $entityManager = $this->getDoctrine()->getManager();
        $coupon = $entityManager->getRepository(Coupon::class)->findOneBy([
            'code' => $coupon_data['code'],
        ]);

        $product_codes_data = [];
        foreach ($products_data as $product_data) {
            $product_codes_data[] = $product_data['code'];
        }

        $products = $entityManager->getRepository(Product::class)->findBy([
            'code' => $product_codes_data,
        ]);


        $price = 0;
        foreach ($products as $product) {
            $price += $product->getPrice();
        }

        $discount = 0;
        if ($coupon) {
            $coupon_type = $coupon->getType();
            if ($coupon_type === Coupon::TYPE['PRICE_FIXED']) {
                $discount += $coupon->getValue();
            }
            if ($coupon_type === Coupon::TYPE['PRICE_PERCENT']) {
                $discount += $coupon->getValue() * $price / 100;
            }
        }

        $total = $price - $discount;

        $order_data = [
            'price' => $price,
            'discount' => $discount,
            'total' => $total,
            'products' => [],
            'coupons' => [],
        ];

        $order = new Order();
        $order->setPrice($order_data['price']);
        $order->setDiscount($order_data['discount']);
        $order->setTotal($order_data['total']);

        $entityManager->persist($order);
        $entityManager->flush();


        foreach ($products as $product) {
            $order_product = new OrderProduct();
            $order_product->setOrder($order);
            $order_product->setCode($product->getCode());
            $order_product->setPrice($product->getPrice());

            $order->addProduct($order_product);

            $entityManager->persist($order_product);
            $entityManager->flush();
        }


        if ($coupon) {
            $order_coupon = new OrderCoupon();
            $order_coupon->setOrder($order);
            $order_coupon->setCode($coupon->getCode());
            $order_coupon->setType($coupon->getType());
            $order_coupon->setValue($coupon->getValue());

            $order->setCoupon($order_coupon);

            $entityManager->persist($order_coupon);
            $entityManager->flush();

            foreach ($coupon->getRules() as $rule) {
                $order_coupon_rule = new OrderCouponRule();
                $order_coupon_rule->setCoupon($order_coupon);
                $order_coupon_rule->setType($rule->getType());
                $order_coupon_rule->setValue($rule->getValue());

                $order_coupon->addRule($order_coupon_rule);

                $entityManager->persist($order_coupon_rule);
                $entityManager->flush();
            }
        }

        $response = [
            'price' => $order->getPrice(),
            'discount' => $order->getDiscount(),
            'total' => $order->getTotal(),
        ];
        return $this->json($response, 201);
    }
}
