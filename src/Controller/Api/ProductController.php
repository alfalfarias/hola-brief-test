<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Service\Product as ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/api/product")
*/
class ProductController extends AbstractController
{
    /**
     * @Route("/products", methods={"GET"}, name="product_index")
     */
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $products = $entityManager->getRepository(Product::class)->findBy([],[
            'id' => 'DESC'
        ]);

        $response = [];

        foreach ($products as $product) {
            $response[] = [
                'id' => $product->getId(),
                'code' => $product->getCode(),
                'price' => $product->getPrice(),
            ];
        }
        return $this->json($response, 200);
    }

    /**
     * @Route("/products", methods={"POST"}, name="product_create")
     */
    public function create(Request $request, ProductService $productService): Response
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

        return $this->json([
            'id' => $product->getId(),
            'code' => $product->getCode(),
            'price' => $product->getPrice(),
        ], 201);
    }
}
