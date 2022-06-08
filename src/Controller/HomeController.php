<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(ArticleRepository $articleRepo): Response
    {
        $title = "Machin Shop";
        return $this->render('home/index.html.twig', [
            'title' => $title,
            'articles' => $articleRepo->findAll()
        ]);
    }
}
