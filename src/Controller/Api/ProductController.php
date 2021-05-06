<?php

namespace App\Controller\Api;

use App\Entity\Product;
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
    public function create(Request $request): Response
    {
        $request_body = json_decode($request->getContent(), true);
        $product_data = $request_body;

        $product = new Product();
        $product->setCode($product_data['code']);
        $product->setPrice($product_data['price']);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($product);
        $entityManager->flush();

        return $this->json([
            'id' => $product->getId(),
            'code' => $product->getCode(),
            'price' => $product->getPrice(),
        ], 201);
    }
}
