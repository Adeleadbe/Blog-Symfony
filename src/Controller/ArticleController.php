<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function new(Request $request)
    {
        //Instanciation d'un nouvel objet Article
        $article = new Article();
        //Création du formulaire
        $form = $this->createForm(ArticleType::class, $article);
        //Traitement du formulaire soumis
        $form->handleRequest($request);

        return $this->render('articles/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //#[Route('/article/{id}', name: 'article')]
    //public function show()
    //{
        //return $this->render('articles/show.html.twig');
    //}
}
