<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\AmazonApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class ProductController extends AbstractController
{

    private ManagerRegistry $doctrine;
    private AmazonApi $AmazonApi;

    public function __construct(ManagerRegistry $doctrine, AmazonApi $amazonApi)
    {
        $this->doctrine = $doctrine;
        $this->AmazonApi = $amazonApi;

    }

    #[Route('/product', name: 'app_product')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $products,
        ]);
    }

}
