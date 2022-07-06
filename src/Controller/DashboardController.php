<?php

namespace App\Controller;

use App\Entity\Attribute;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\SubCategory;
use App\Form\AsinFormType;
use App\Form\CategoryFormType;
use App\Form\SubCategoryFormType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\SubCategoryRepository;
use App\Repository\UserRepository;
use App\Service\AmazonApi;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard')]
class DashboardController extends AbstractController
{
    private ManagerRegistry $doctrine;
    private AmazonApi $AmazonApi;

    public function __construct(ManagerRegistry $doctrine, AmazonApi $amazonApi)
    {
        $this->doctrine = $doctrine;
        $this->AmazonApi = $amazonApi;

    }

    #[Route('/', name: 'app_dashboard')]
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    #[Route('/category-list', name: 'category_list')]
    public function categoryList(Request $request, CategoryRepository $categoryRepository): Response
    {

        $categories = $categoryRepository->findAll();
//        Permet de retourner la liste des key
//        $keys = array_keys((array)$categories[0]);

        return $this->render('dashboard/category_list.html.twig', [
            'categories' => $categories,
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
        return $this->render('dashboard/add_category.html.twig', [
            'Form' => $form->createView(),
        ]);
    }

    #[Route('/subcategory-list', name: 'subcategory_list')]
    public function subcategoryList(Request $request, SubCategoryRepository $subCategoryRepository): Response
    {
        $subCategories = $subCategoryRepository->findAll();
        return $this->render('dashboard/subcategory_list.html.twig', [
            'subcategories' => $subCategories,
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
        return $this->render('dashboard/add_subcategory.html.twig', [
            'Form' => $form->createView(),
        ]);
    }

    #[Route('/product-list', name: 'product_list')]
    public function productList(Request $request, ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->render('dashboard/product_list.html.twig', [
            'products' => $products,
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
            $pathProduct = $form->getData()['pathProduct'];
            if ($form->get('add')->isClicked()){
                $this->scraperAmazonProduct($asin, $subCat, $rank, $pathProduct);
            }
            if ($form->get('update')->isClicked()){
                $this->updateAmazonProduct($asin, $subCat, $rank, $pathProduct);
            }
        }
        return $this->render('dashboard/add_product.html.twig', [
            'Form' => $form->createView(),
        ]);
    }

    #[Route('/user-list', name: 'user_list')]
    public function userList(Request $request, UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('dashboard/user_list.html.twig', [
            'users' => $users,
        ]);
    }


    //Scrap product with asin to persist into db
    public function scraperAmazonProduct($asin, $subCat, $rank, $pathProduct){

        $ApiProduct = $this->AmazonApi->fetchAmazonProduct($asin);

        $entityManager = $this->doctrine->getManager();

//        B09G9C21MH

        $product = new Product();
        $product->setTitle($ApiProduct['product']['title']);
        $product->setAsin($asin);
        $product->setPathProduct($pathProduct);
        if (isset($ApiProduct['product']['rating'])){
            $product->setRating($ApiProduct['product']['rating']);
        }else {
            $product->setRating(0);
        }
        if (isset($ApiProduct['product']['buybox_winner']['price']['value'])){
            $product->setPrice($ApiProduct['product']['buybox_winner']['price']['value']);
        }else {
            $product->setPrice(0);
        }
        $product->setAlink('empty');
        if (isset($ApiProduct['product']['description'])){
            $product->setDescription($ApiProduct['product']['description']);
        }else {
            $product->setDescription('nodesc');
        }
        if (isset($ApiProduct['product']['ratings_total'])){
            $product->setRatingTotal($ApiProduct['product']['ratings_total']);
        }else {
            $product->setRatingTotal(0);
        }
        if (isset($ApiProduct['product']['reviews_total'])){
            $product->setReviewTotal($ApiProduct['product']['reviews_total']);
        }else {
            $product->setReviewTotal(0);
        }
        $product->setImage($ApiProduct['product']['main_image']['link']);
        $product->setSubcategory($subCat);
        $product->setRank($rank);

//        Persist attributes if exist
        if (isset($ApiProduct['product']['attributes'])){

            $attrNb = count($ApiProduct['product']['attributes']) - 1;
            for($i=0; $i <= $attrNb; $i++) {
                ${'attributes' . $i} = new Attribute();
                ${'attributes' . $i}->setProduct($product);
                ${'attributes' . $i}->setName($ApiProduct['product']['attributes'][$i]['name']);
                ${'attributes' . $i}->setValue($ApiProduct['product']['attributes'][$i]['value']);
                ${'attributes' . $i}->setState(1);
                $entityManager->persist(${'attributes' . $i});
            }
        }



        $entityManager->persist($product);
        $entityManager->flush();

    }

    //update product with asin to persist into db
    public function updateAmazonProduct($asin, $subCat, $rank, $pathProduct){
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
