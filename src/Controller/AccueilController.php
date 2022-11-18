<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\RankRepository;
use App\Repository\SubCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(CategoryRepository $categoryRepository, ProductRepository $productRepository): Response
    {
        $session = new Session();
        $session->clear();
        $session->set('categories', $categoryRepository->findAll());

        $products = $productRepository->findAll();

        return $this->render('home.html.twig', [
            'products' => $products,
        ]);
    }
}
