<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[Route(name: 'app_')]
class ArticleController extends AbstractController
{
    #[Route('/article', name: 'article')]
    public function index(EntityManagerInterface $entityManager, AuthorizationCheckerInterface $authorizationChecker): Response
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
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        // Création du formulaire
        $form = $this->createForm(ArticleType::class);
        // Traitement du formulaire soumis
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $article->setAuthor($this->getUser()); // Set the user as the author

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('app_article');
        }

        return $this->render('articles/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/article/{id}', name: 'article_show')]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        $article = $entityManager->getRepository(Article::class)->findOneBy(['id' => $id]);

        if (is_null($article)) {
            return $this->redirectToRoute('app_article');
        }

        return $this->render('articles/show.html.twig', [
            'article' => $article
        ]);
    }

    #[Route('/article/edit/{id}', name: 'article_edit')]
    public function edit(Request $request, int $id, EntityManagerInterface $entityManager, AuthorizationCheckerInterface $authorizationChecker): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé');
        }

        if (!$authorizationChecker->isGranted('edit', $article)) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier cet article');
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_article');
        }

        return $this->render('articles/edit.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
        ]);
    }

    #[Route('/article/delete/{id}', name: 'article_delete')]
    public function delete(int $id, EntityManagerInterface $entityManager, AuthorizationCheckerInterface $authorizationChecker): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé');
        }

        if (!$authorizationChecker->isGranted('delete', $article)) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à supprimer cet article');
        }

        // Vérification de l'auteur de l'article
        if ($article->getAuthor() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à supprimer cet article');
        }

        $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('app_article');
    }
}
