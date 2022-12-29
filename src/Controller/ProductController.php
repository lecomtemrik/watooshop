<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\RankRepository;
use App\Repository\SubCategoryRepository;
use App\Service\AmazonApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class ProductController extends AbstractController
{

    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

//    #[Route('/products', name: 'products')]
//    public function index(ProductRepository $productRepository): Response
//    {
//        $products = $productRepository->findAll();
//
//        return $this->render('product/best.html.twig', [
//            'products' => $products,
//        ]);
//
//    }

    #[Route('/categories', name: 'category')]
    public function category(CategoryRepository $categoryRepository): Response
    {
       $categories = $categoryRepository->findAll();

        return $this->render('category.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/{pathCategory}/{pathSubCategory}/{pathProduct}', name: 'product')]
    public function product(ProductRepository $productRepository, string $pathProduct): Response
    {
        $product = $productRepository->findOneBy(['pathProduct'=> $pathProduct]);

        return $this->render('product/product.html.twig', [
            'product' => $product,
        ]);

    }

    #[Route('/{pathCategory}/{pathSubCategory}', name: 'subcat')]
    public function subCat(SubCategoryRepository $subCategoryRepository, string $pathSubCategory): Response
    {
        $subCategory = $subCategoryRepository->findBy(['pathSubCategory'=> $pathSubCategory]);
        foreach ($subCategory as $subCat){
            $products = $subCat->getProducts();
        }
        return $this->render('product/index.html.twig', [
            'products' => $products,
            'image' => $products->getValues()[0]->getSubcategory()->getCategory()->getImage(),
            'category' => $products->getValues()[0]->getSubcategory()->getCategory()->getTitle(),
            'sousCategory' => $products->getValues()[0]->getSubcategory()->getTitle(),
        ]);

    }


    #[Route('/bestsellers', name: 'best_sellers')]
    public function bestSellers(RankRepository $rankRepository): Response
    {
        $ranks = $rankRepository->findBy(['title'=>'meilleure vente']);
        foreach ($ranks as $rank){
            $products = $rank->getProducts();
        }

        return $this->render('product/best.html.twig', [
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

        return $this->render('product/best.html.twig', [
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

        return $this->render('product/best.html.twig', [
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

        return $this->render('product/best.html.twig', [
            'products' => $products,
        ]);
    }

}
