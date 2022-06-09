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

//        $this->scraperAmazonProduct('B07568DFCH');
////        $this->updateAmazonProduct('B07Y8SF6ZY');
//        $ApiProducts = $this->AmazonApi->fetchAmazonProduct('B07568DFCH');
//        dump($ApiProducts);
//        dump($ApiProducts['product']['images'][0]);

        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $products,
//            'ApiProducts' => $ApiProducts,
        ]);
    }

    //Scrap product with asin to persist into db
    public function scraperAmazonProduct($asin){

        $ApiProduct = $this->AmazonApi->fetchAmazonProduct($asin);

        $entityManager = $this->doctrine->getManager();

        dump($ApiProduct['product']);

        $product = new Product();
        $product->setTitle($ApiProduct['product']['title']);

        $product->setAsin($asin);
        $product->setRating($ApiProduct['product']['rating']);
        $product->setPrice($ApiProduct['product']['buybox_winner']['price']['value']);

        $product->setAlink('empty');
        if (isset($ApiProduct['product']['description'])){
            $product->setDescription($ApiProduct['product']['description']);
        }
        $product->setLevel('best');
        $product->setImage($ApiProduct['product']['main_image']['link']);
        $entityManager->persist($product);
        $entityManager->flush();

    }

    //update product with asin to persist into db
    public function updateAmazonProduct($asin){
        $ApiProduct = $this->AmazonApi->fetchAmazonProduct($asin);
        $entityManager = $this->doctrine->getManager();

        $product = $this->doctrine->getRepository(Product::class)->findOneBy(['asin'=>$asin]);
        $product->setTitle($ApiProduct['product']['title']);

        $product->setAsin($asin);
        $product->setRating($ApiProduct['product']['rating']);
        $product->setPrice($ApiProduct['product']['buybox_winner']['price']['value']);

        $product->setAlink('empty');
        $product->setDescription($ApiProduct['product']['description']);
        $product->setLevel('best');
        $product->setImage($ApiProduct['product']['main_image']['link']);
        $entityManager->persist($product);
        $entityManager->flush();
    }
}
