<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Form\NewsletterFormType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{

    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route('/', name: 'app_accueil')]
    public function index(CategoryRepository $categoryRepository, ProductRepository $productRepository, Request $request): Response
    {
        $session = $request->getSession();
        $session->clear();
        $session->set('categories', $categoryRepository->findAll());

        $products = $productRepository->findAll();

        $form = $this->createForm(NewsletterFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            dump($form->getErrors());
            $entityManager = $this->doctrine->getManager();
            $newsletter = new Newsletter();
            $newsletter->setEmailAdresse($form->getData()->getEmailAdresse());
            $entityManager->persist($newsletter);
            $entityManager->flush();

        }

        return $this->render('home.html.twig', [
            'products' => $products,
            'Form' => $form->createView()
        ]);
    }
}
