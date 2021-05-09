<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Service\Product as ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
* @Route("/api/product")
*/
class ProductController extends AbstractController
{
    /**
     * @Route("/products", methods={"GET"}, name="product_index")
     */
    public function index(SerializerInterface $serializer): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $products = $entityManager->getRepository(Product::class)->findBy([],[
            'id' => 'DESC'
        ]);

        $response = [];
        foreach ($products as $product) {
            $product_response = $serializer->normalize($product, null, [
                AbstractNormalizer::ATTRIBUTES => [
                    'id', 'code', 'price',
                ]
            ]);
            $response[] = $product_response;
        }
        return $this->json($response, 200);
    }

    /**
     * @Route("/products", methods={"POST"}, name="product_create")
     */
    public function create(Request $request, SerializerInterface $serializer, ProductService $productService): Response
    {
        $request_body = json_decode($request->getContent(), true);
        $product_data = $request_body;

        $entityManager = $this->getDoctrine()->getManager();

        //Validate
        $exists = !!$entityManager->getRepository(Product::class)->findOneBy([
            'code' => $product_data['code'],
        ]);
        if ($exists) {
            return $this->json(['code' => [
                    'El código ya existe'
                ],
            ], 422);
        }

        $product = new Product();
        $product->setCode($product_data['code']);
        $product->setPrice($product_data['price']);

        $productService->create($product);

        $response = $serializer->normalize($product, null, [
            AbstractNormalizer::ATTRIBUTES => [
                'id', 'code', 'price',
            ]
        ]);
        return $this->json($response, 201);
    }
}
