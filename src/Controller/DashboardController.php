<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\SubCategory;
use App\Form\AsinFormType;
use App\Form\CategoryFormType;
use App\Form\SubCategoryFormType;
use App\Service\AmazonApi;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    private ManagerRegistry $doctrine;
    private AmazonApi $AmazonApi;

    public function __construct(ManagerRegistry $doctrine, AmazonApi $amazonApi)
    {
        $this->doctrine = $doctrine;
        $this->AmazonApi = $amazonApi;

    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    #[Route('/add-category', name: 'add_category')]
    public function addCategory(Request $request): Response
    {
        $form = $this->createForm(CategoryFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->doctrine->getManager();
            $category = new Category();
            $category->setTitle($form->getData()->getTitle());
            $category->setPathCategory($form->getData()->getPathCategory());
            $entityManager->persist($category);
            $entityManager->flush();

        }
        return $this->render('dashboard/admin_product.html.twig', [
            'Form' => $form->createView(),
        ]);
    }

    #[Route('/add-subcategory', name: 'add_subcategory')]
    public function addSubCategory(Request $request): Response
    {
        $form = $this->createForm(SubCategoryFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->doctrine->getManager();
            $subCategory = new SubCategory();
            $subCategory->setTitle($form->getData()->getTitle());
            $subCategory->setPathSubCategory($form->getData()->getPathSubCategory());
            $subCategory->setCategory($form->getData()->getCategory());
            $entityManager->persist($subCategory);
            $entityManager->flush();

        }
        return $this->render('dashboard/admin_product.html.twig', [
            'Form' => $form->createView(),
        ]);
    }

    #[Route('/add-product', name: 'add_product')]
    public function addProduct(Request $request): Response
    {
        $form = $this->createForm(AsinFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $asin = $form->getData()['asin'];
            $subCat = $form->getData()['SubCategory'];
            $rank = $form->getData()['Rank'];
            if ($form->get('add')->isClicked()){
                $this->scraperAmazonProduct($asin, $subCat, $rank);
            }
            if ($form->get('update')->isClicked()){
                $this->updateAmazonProduct($asin, $subCat, $rank);
            }
        }
        return $this->render('dashboard/admin_product.html.twig', [
            'Form' => $form->createView(),
        ]);
    }


    //Scrap product with asin to persist into db
    public function scraperAmazonProduct($asin, $subCat, $rank){

        $ApiProduct = $this->AmazonApi->fetchAmazonProduct($asin);

        $entityManager = $this->doctrine->getManager();

        $product = new Product();
        $product->setTitle($ApiProduct['product']['title']);

        $product->setAsin($asin);
        if (isset($ApiProduct['product']['rating'])){
            $product->setRating($ApiProduct['product']['rating']);
        }else {
            $product->setRating(0);
        }
        $product->setPrice($ApiProduct['product']['buybox_winner']['price']['value']);

        $product->setAlink('empty');
        if (isset($ApiProduct['product']['description'])){
            $product->setDescription($ApiProduct['product']['description']);
        }else {
            $product->setDescription('nodesc');
        }
        $product->setImage($ApiProduct['product']['main_image']['link']);
        $product->setSubcategory($subCat);
        $product->setRank($rank);
        $entityManager->persist($product);
        $entityManager->flush();

    }

    //update product with asin to persist into db
    public function updateAmazonProduct($asin, $subCat, $rank){
        $ApiProduct = $this->AmazonApi->fetchAmazonProduct($asin);
        $entityManager = $this->doctrine->getManager();

        $product = $this->doctrine->getRepository(Product::class)->findOneBy(['asin'=>$asin]);
        $product->setTitle($ApiProduct['product']['title']);

        $product->setAsin($asin);
        $product->setRating($ApiProduct['product']['rating']);
        $product->setPrice($ApiProduct['product']['buybox_winner']['price']['value']);

        $product->setAlink('empty');
        $product->setDescription($ApiProduct['product']['description']);
        $product->setImage($ApiProduct['product']['main_image']['link']);
        $product->setSubcategory($subCat);
        $product->setRank($rank);
        $entityManager->persist($product);
        $entityManager->flush();
    }

}
