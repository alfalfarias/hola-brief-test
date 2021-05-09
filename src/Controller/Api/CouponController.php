<?php

namespace App\Controller\Api;

use App\Entity\Coupon;
use App\Entity\CouponRule as Rule;
use App\Service\Coupon as CouponService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
* @Route("/api/coupon")
*/
class CouponController extends AbstractController
{
    /**
     * @Route("/coupons", methods={"GET"}, name="coupon_index")
     */
    public function index(SerializerInterface $serializer): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $coupons = $entityManager->getRepository(Coupon::class)->findBy([],[
            'id' => 'DESC'
        ]);

        $response = [];
        foreach ($coupons as $coupon) {
            $coupon_response = $serializer->normalize($coupon, null, [
                AbstractNormalizer::ATTRIBUTES => [
                    'id', 
                    'code', 
                    'type',
                    'value',
                    'rules' => [
                        'type',
                        'value',
                    ],
                ]
            ]);
            $response[] = $coupon_response;
        }
        return $this->json($response, 200);
    }

    /**
     * @Route("/coupons", methods={"POST"}, name="coupon_create")
     */
    public function create(Request $request, SerializerInterface $serializer, CouponService $couponService): Response
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

        $response = $serializer->normalize($coupon, null, [
            AbstractNormalizer::ATTRIBUTES => [
                'id', 
                'code', 
                'type',
                'value',
                'rules' => [
                    'type',
                    'value',
                ],
            ]
        ]);
        return $this->json($response, 201);
    }
}
