<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


#[Route('/article')]
class ArticleController extends AbstractController
{

    #[Route('/like/{idArticle}', name: 'app_article_like', methods: ['GET'])]
    public function likeArticle($idArticle, EntityManagerInterface $entityManager, ArticleRepository $articleRepository): Response
    {
        // Retrieve the Article entity by id
        $article = $articleRepository->find($idArticle);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        // Increment the number of likes
        $article->setNbLikes($article->getNbLikes() + 1);
        $entityManager->flush();

        // Redirect back to the index page or return a JsonResponse with updated data
        return $this->redirectToRoute('app_article_index');
    }


    #[Route('/admin', name: 'app_article_admin_index', methods: ['GET'])]
    public function admin_index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/admin_index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }


    #[Route('/admin/new', name: 'app_article_admin_new', methods: ['GET', 'POST'])]
    public function admin_new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/admin_new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }


    #[Route('/admin/{id}', name: 'app_article_admin_show', methods: ['GET'])]
    public function admin_show(Article $article): Response
    {
        return $this->render('article/admin_show.html.twig', [
            'article' => $article,
        ]);
    }



    #[Route('/admin/{id}/edit', name: 'app_article_admin_edit', methods: ['GET', 'POST'])]
    public function admin_edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_article_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/admin_edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/admin/{id}', name: 'app_article_admin_delete', methods: ['POST'])]
    public function admin_delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_article_admin_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/', name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }
}
