<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Fonctions;

class HomeController extends AbstractController
{

    private $fonctions;

    public function __construct(Fonctions $fonctions) {
        $this->fonctions = $fonctions;
    }

    /**
     * @Route("/", name="app_home")
     */
    public function index(ArticleRepository $articleRepo): Response
    {
        $title = "Home";
        dump($this->fonctions->mult(5,2));
        $result = $this->fonctions->mult(5,10);

        return $this->render('home/index.html.twig', [
            'title' => $title,
            'result' => $result,
            'articles' => $articleRepo->findAll()
        ]);
    }
}
