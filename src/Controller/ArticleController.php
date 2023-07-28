<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'app_')]
class ArticleController extends AbstractController
{
    #[Route('/article', name: 'article')]
    public function index()
    {
        return $this->render('articles/index.html.twig');
    }

    #[Route('/article/new', name: 'article_new')]
    public function new()
    {
        return $this->render('articles/new.html.twig');
    }

    //#[Route('/article/{id}', name: 'article')]
    //public function show()
    //{
        //return $this->render('articles/show.html.twig');
    //}
}
