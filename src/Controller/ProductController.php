<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\RankRepository;
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
        foreach ($products as $product) {
            dump($product->getSubcategory()->getTitle());
        }

        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $products,
        ]);

    }


    #[Route('/bestsellers', name: 'best_sellers')]
    public function bestSellers(RankRepository $rankRepository): Response
    {
        $ranks = $rankRepository->findBy(['title'=>'meilleure vente']);
        foreach ($ranks as $rank){
            $products = $rank->getProducts();
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/bestprice', name: 'best_price')]
    public function bestPrice(RankRepository $rankRepository): Response
    {
        $ranks = $rankRepository->findBy(['title'=>'meilleur prix']);
        foreach ($ranks as $rank){
            $products = $rank->getProducts();
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/bestquality', name: 'best_quality')]
    public function bestQuality(RankRepository $rankRepository): Response
    {
        $ranks = $rankRepository->findBy(['title'=>'meilleure qualité']);
        foreach ($ranks as $rank){
            $products = $rank->getProducts();
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/pricequality', name: 'price_quality')]
    public function priceQuality(RankRepository $rankRepository): Response
    {
        $ranks = $rankRepository->findBy(['title'=>'Rapport qualité/prix']);
        foreach ($ranks as $rank){
            $products = $rank->getProducts();
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

}
