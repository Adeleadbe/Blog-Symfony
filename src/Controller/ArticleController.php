<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[Route(name: 'app_')]
class ArticleController extends AbstractController
{
    #[Route('/article', name: 'article')]
    public function index(EntityManagerInterface $entityManager, AuthorizationCheckerInterface $authorizationChecker)
    {
        if (!$authorizationChecker->isGranted('ROLE_USER')) {
            throw $this->createAccessDeniedException('Accés refusé.');
        }

        $articles = $entityManager->getRepository(Article::class)->findAll();
        
        return $this->render('articles/index.html.twig', [
            'articles' => $articles
        ]);
    }

    #[Route('/article/new', name: 'article_new')]
    public function new(Request $request, EntityManagerInterface $manager)
    {
        //Instanciation d'un nouvel objet Article
        $article = new Article();
        //Création du formulaire
        $form = $this->createForm(ArticleType::class, $article);
        //Traitement du formulaire soumis
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('app_article');
        }

        return $this->render('articles/new.html.twig', [
            'form' => $form->createView(),
        ]); 
    }

    #[Route('/article/{id}', name: 'article_show')]
    public function show($id, EntityManagerInterface $entityManager)
    {
        $article = $entityManager->getRepository(Article::class)->findOneBy(['id' => $id]);

        if(is_null($article)){
            return $this->redirectToRoute('app_article');
        }

        return $this->render('articles/show.html.twig', [
            'article' => $article
        ]);
    }

    #[Route('/article/edit/{id}', name: 'article_edit')]
    public function edit(Request $request, int $id, EntityManagerInterface $entityManager){
        
        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé');
        }

        if(!$authorizationChecker->isGranted)

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $updatedArticle = $entityManager->getRepository(Article::class)->find($id);

            return $this->redirectToRoute('app_article', [
                'article' => $updatedArticle,
            ]);
        }

        return $this->render('articles/new.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
        ]);
    }

    #[Route('/article/delete/{id}', name: 'article_delete')]
    public function delete(int $id, EntityManagerInterface $entityManager){
        
        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé');
        }

        $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('app_article');
    }
}
