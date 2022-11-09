<?php

namespace App\Controller;

use App\Entity\Attribute;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Review;
use App\Entity\SubCategory;
use App\Entity\User;
use App\Form\AsinFormType;
use App\Form\AttributeFormType;
use App\Form\CategoryFormType;
use App\Form\RegistrationFormType;
use App\Form\ReviewFormType;
use App\Form\SubCategoryFormType;
use App\Repository\AttributeRepository;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\ReviewRepository;
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

        return $this->render('dashboard/category/category_list.html.twig', [
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
            $category->setImage($form->getData()->getImage());
            $entityManager->persist($category);
            $entityManager->flush();

        }
        return $this->render('dashboard/category/add_review.html.twig', [
            'Form' => $form->createView(),
        ]);
    }

    #[Route('/delete-category/{id}', name: 'delete_category', methods: ['POST'])]
    public function deleteCategory(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $categoryRepository->remove($category);
        }

        return $this->redirectToRoute('category_list', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/edit-category/{id}', name: 'edit_category', methods: ['GET', 'POST'])]
    public function editCategory(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->add($category);
            return $this->redirectToRoute('category_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dashboard/category/edit.html.twig', [
            'category' => $category,
            'Form' => $form,
        ]);
    }


    #[Route('/subcategory-list', name: 'subcategory_list')]
    public function subcategoryList(Request $request, SubCategoryRepository $subCategoryRepository): Response
    {
        $subCategories = $subCategoryRepository->findAll();
        return $this->render('dashboard/subcategory/subcategory_list.html.twig', [
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
        return $this->render('dashboard/subcategory/add_subcategory.html.twig', [
            'Form' => $form->createView(),
        ]);
    }

    #[Route('/delete-subcategory/{id}', name: 'delete_subcategory', methods: ['POST'])]
    public function deleteSubCategory(Request $request, SubCategory $subCategory, SubCategoryRepository $subCategoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$subCategory->getId(), $request->request->get('_token'))) {
            $subCategoryRepository->remove($subCategory);
        }

        return $this->redirectToRoute('subcategory_list', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/edit-subcategory/{id}', name: 'edit_subcategory', methods: ['GET', 'POST'])]
    public function editSubCategory(Request $request, SubCategory $subCategory, SubCategoryRepository $subCategoryRepository): Response
    {
        $form = $this->createForm(SubCategoryFormType::class, $subCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subCategoryRepository->add($subCategory);
            return $this->redirectToRoute('subcategory_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dashboard/subcategory/edit.html.twig', [
            'subcategory' => $subCategory,
            'Form' => $form,
        ]);
    }

    #[Route('/product-list', name: 'product_list')]
    public function productList(Request $request, ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->render('dashboard/product/product_list.html.twig', [
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
            $entityManager = $this->doctrine->getManager();
            if ($form->get('add')->isClicked()){
                $product = new Product();
                $this->amazonProduct($product, $entityManager, $asin, $subCat, $rank, $pathProduct);
            }
            if ($form->get('update')->isClicked()){
                $product = $this->doctrine->getRepository(Product::class)->findOneBy(['asin'=>$asin]);
                $this->amazonProduct($product, $entityManager, $asin, $subCat, $rank, $pathProduct);
            }
        }
        return $this->render('dashboard/product/add_product.html.twig', [
            'Form' => $form->createView(),
        ]);
    }

    //@todo supprimer review + attributes en même temps
    #[Route('/delete-product/{id}', name: 'delete_product', methods: ['POST'])]
    public function deleteProduct(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product);
        }

        return $this->redirectToRoute('product_list', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/edit-product/{id}', name: 'edit_product', methods: ['GET', 'POST'])]
    public function editProduct(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(AsinFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->add($product);
            return $this->redirectToRoute('product_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dashboard/product/edit.html.twig', [
            'product' => $product,
            'Form' => $form,
        ]);
    }

    #[Route('/attribute-list', name: 'attribute_list')]
    public function attributeList(Request $request, AttributeRepository $attributeRepository): Response
    {
        $attributes = $attributeRepository->findAll();
        return $this->render('dashboard/attribute/attribute_list.html.twig', [
            'attributes' => $attributes,
        ]);
    }

    #[Route('/add-attribute', name: 'add_attribute')]
    public function addAttribute(Request $request): Response
    {
        $form = $this->createForm(AttributeFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->doctrine->getManager();
            $attribute = new Attribute();
            $attribute->setName($form->getData()->getName());
            $attribute->setValue($form->getData()->getValue());
            $attribute->setState($form->getData()->getState());
            $attribute->setProduct($form->getData()->getProduct());
            $entityManager->persist($attribute);
            $entityManager->flush();

        }
        return $this->render('dashboard/attribute/add_review.html.twig', [
            'Form' => $form->createView(),
        ]);
    }

    #[Route('/review-list', name: 'review_list')]
    public function reviewList(Request $request, ReviewRepository $reviewRepository): Response
    {
        $reviews = $reviewRepository->findAll();
        return $this->render('dashboard/review/review_list.html.twig', [
            'reviews' => $reviews,
        ]);
    }

    #[Route('/add-review', name: 'add_review')]
    public function addReview(Request $request): Response
    {
        $form = $this->createForm(ReviewFormType::class);
        $form->handleRequest($request);
        dump($form);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->doctrine->getManager();
            $review = new Review();
            $review->setTitle($form->getData()->getTitle());
            $review->setBody($form->getData()->getBody());
            $review->setRating($form->getData()->getRating());
            $review->setCountry($form->getData()->getCountry());
            $review->setDate($form->getData()->getDate());
            $review->setProfileName($form->getData()->getProfileName());
            $review->setProfilePicture($form->getData()->getProfilePicture());
            $review->setProduct($form->getData()->getProduct());
            $entityManager->persist($review);
            $entityManager->flush();

        }
        return $this->render('dashboard/review/add_review.html.twig', [
            'Form' => $form->createView(),
        ]);
    }

    #[Route('/user-list', name: 'user_list')]
    public function userList(Request $request, UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('dashboard/user/user_list.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/delete-user/{id}', name: 'delete_user', methods: ['POST'])]
    public function deleteUser(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user);
        }

        return $this->redirectToRoute('user_list', [], Response::HTTP_SEE_OTHER);
    }
    //@todo Modification password exemple RegistrationController
    #[Route('/edit-user/{id}', name: 'edit_user', methods: ['GET', 'POST'])]
    public function editUser(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user);
            return $this->redirectToRoute('user_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dashboard/user/edit.html.twig', [
            'user' => $user,
            'Form' => $form,
        ]);
    }

    public function amazonProduct($product, $entityManager, $asin, $subCat, $rank, $pathProduct){
        $ApiProduct = $this->AmazonApi->fetchAmazonProduct($asin);
        $product->setTitle($ApiProduct['product']['title']);
        $product->setAsin($asin);
        $product->setPathProduct($pathProduct);
        $product->setBrand($ApiProduct['product']['brand']);
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

        if (isset($ApiProduct['product']['top_reviews'])){

            $reviewsNb = count($ApiProduct['product']['top_reviews']) - 1;
            for($i=0; $i <= $reviewsNb; $i++) {
                ${'reviews' . $i} = new Review();
                ${'reviews' . $i}->setProduct($product);
                ${'reviews' . $i}->setTitle($ApiProduct['product']['top_reviews'][$i]['title']);
                ${'reviews' . $i}->setBody($ApiProduct['product']['top_reviews'][$i]['body']);
                ${'reviews' . $i}->setRating($ApiProduct['product']['top_reviews'][$i]['rating']);
                ${'reviews' . $i}->setDate($ApiProduct['product']['top_reviews'][$i]['date']['utc']);
                ${'reviews' . $i}->setProfileName($ApiProduct['product']['top_reviews'][$i]['profile']['name']);
                if (isset($ApiProduct['product']['top_reviews'][$i]['profile']['image'])){
                    ${'reviews' . $i}->setProfilePicture($ApiProduct['product']['top_reviews'][$i]['profile']['image']);
                }
                ${'reviews' . $i}->setCountry($ApiProduct['product']['top_reviews'][$i]['review_country']);
                $entityManager->persist(${'reviews' . $i});

            }
        }


        $entityManager->persist($product);
        $entityManager->flush();

    }

}
