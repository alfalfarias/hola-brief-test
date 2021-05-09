<?php

namespace App\Controller\Api;

use App\Entity\Coupon;
use App\Entity\CouponRule as Rule;
use App\Service\Coupon as CouponService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/api/coupon")
*/
class CouponController extends AbstractController
{
    /**
     * @Route("/coupons", methods={"GET"}, name="coupon_index")
     */
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $coupons = $entityManager->getRepository(Coupon::class)->findBy([],[
            'id' => 'DESC'
        ]);

        $response = [];

        foreach ($coupons as $coupon) {
            $rules_response = [];
            foreach ($coupon->getRules() as $rule) {
                $rules_response[] = [
                    'type' => $rule->getType(),
                    'value' => $rule->getValue(),
                ];
            }

            $response[] = [
                'id' => $coupon->getId(),
                'code' => $coupon->getCode(),
                'type' => $coupon->getType(),
                'value' => $coupon->getValue(),
                'rules' => $rules_response,
            ];
        }

        return $this->json($response, 200);
    }

    /**
     * @Route("/coupons", methods={"POST"}, name="coupon_create")
     */
    public function create(Request $request, CouponService $couponService): Response
    {
        $request_body = json_decode($request->getContent(), true);
        $coupon_data = $request_body['coupon'];
        $rules_data = $request_body['rules'];

        $entityManager = $this->getDoctrine()->getManager();

        $exists = !!$entityManager->getRepository(Coupon::class)->findOneBy([
            'code' => $coupon_data['code'],
        ]);
        if ($exists) {
            return $this->json(['code' => [
                    'El código ya existe'
                ],
            ], 422);
        }

        $coupon = new Coupon();
        $coupon->setCode($coupon_data['code']);
        $coupon->setType($coupon_data['type']);
        $coupon->setValue($coupon_data['value']);

        $rules = [];
        foreach ($rules_data as $rule_data) {
            $rule = new Rule();
            $rule->setType($rule_data['type']);
            $rule->setValue($rule_data['value']);
            $rules[] = $rule; 
        }

        $couponService->create($coupon, $rules);

        $rules_response = [];
        foreach ($coupon->getRules() as $rule) {
            $rules_response[] = [
                'type' => $rule->getType(),
                'value' => $rule->getValue(),
            ];
        }

        $response = [
            'id' => $coupon->getId(),
            'code' => $coupon->getCode(),
            'type' => $coupon->getType(),
            'value' => $coupon->getValue(),
            'rules' => $rules_response,
        ];

        return $this->json($response, 201);
    }
}
