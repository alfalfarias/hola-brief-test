<?php

namespace App\Controller;

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
        $query = $entityManager->getRepository(Product::class)->findAll();

        $data = [];

        foreach ($query as $key => $value) {
            $data[] = [
                // 'id' => $value->getId(),
                'code' => $value->getCode(),
                'price' => $value->getPrice(),
            ];
        }
        return $this->json($data, 200);
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
