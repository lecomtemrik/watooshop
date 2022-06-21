<?php

namespace App\Controller;

use App\Repository\SubCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(SubCategoryRepository $subCategoryRepository): Response
    {
        $session = new Session();
        $session->clear();
        $session->set('subCats', $subCategoryRepository->findAll());

        return $this->render('base.html.twig');
    }
}
