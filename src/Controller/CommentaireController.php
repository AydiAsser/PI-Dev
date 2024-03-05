<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Article;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Service\ForbiddenWordChecker;

#[Route('/commentaire')]
class CommentaireController extends AbstractController
{


    private $forbiddenWordChecker;

    public function __construct(ForbiddenWordChecker $forbiddenWordChecker)
    {
        $this->forbiddenWordChecker = $forbiddenWordChecker;
    }




    #[Route('/', name: 'app_commentaire_index', methods: ['GET'])]
    public function index(CommentaireRepository $commentaireRepository): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
        ]);
    }

    #[Route('/admin', name: 'app_commentaire_admin_index', methods: ['GET'])]
    public function admin_index(CommentaireRepository $commentaireRepository): Response
    {
        return $this->render('commentaire/admin/admin_index.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_commentaire_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Retrieve the article ID and comment text from the request
        $articleId = $request->request->get('article');
        $commentText = $request->request->get('comment');
    
        // Retrieve the article entity by its ID
        $article = $this->getDoctrine()->getRepository(Article::class)->find($articleId);
    
        if ($this->forbiddenWordChecker->containsForbiddenWord($commentText)) {

            return new Response('Inappropriate content', 403);

        }
        else {

        
        // Create a new Commentaire entity and set its properties
        $commentaire = new Commentaire();
        $commentaire->setContenu($commentText);
    
        // Associate the comment with the article
        $commentaire->setArticle($article);
    
        $article->setNbComments($article->getNbComments() + 1);
    
        // Persist the comment to the database
        $entityManager->persist($commentaire);
        $entityManager->flush();
    }

// Redirect the user to the appropriate page
return $this->redirectToRoute('app_article_show', ['id' => $articleId]);

    }
    

    #[Route('/admin/new', name: 'app_commentaire_admin_new', methods: ['GET', 'POST'])]
    public function admin_new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        $article = $commentaire->getArticle();
    
        // Check if the CSRF token is valid
        // if ($this->isCsrfTokenValid('delete' . $commentaire->getId(), $request->request->get('_token'))) {
       

        if ($form->isSubmitted() && $form->isValid()) {

            
            if ($this->forbiddenWordChecker->containsForbiddenWord($commentaire->getContenu())) {

                return new Response('Inappropriate content', 403);
    
            }

           
    
            // Decrement nbComments in the associated article
            $entityManager->persist($commentaire);
            $article->setNbComments($article->getNbComments() + 1);
            $entityManager->persist($article);
            
            $entityManager->flush();

            return $this->redirectToRoute('app_article_admin_show', ['id' => $commentaire->getArticle()->getId()]);
        }

        return $this->renderForm('commentaire/admin/admin_new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_commentaire_show', methods: ['GET'])]
    public function show(Commentaire $commentaire): Response
    {
        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    #[Route('/admin/{id}', name: 'app_commentaire_admin_show', methods: ['GET'])]
    public function admin_show(Commentaire $commentaire): Response
    {
        return $this->render('commentaire/admin/admin_show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commentaire_edit')]
    public function edit(Request $request, Commentaire $commentaire): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $updatedComment = $request->request->get('contenu');

        $commentaire->setContenu($updatedComment);
        $entityManager->flush();

        return new Response('Comment updated successfully', 200);
    }





    #[Route('/admin/{id}/edit', name: 'app_commentaire_admin_edit', methods: ['GET', 'POST'])]
    public function admin_edit(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_article_admin_show', ['id' => $commentaire->getArticle()->getId()]);

        }

        return $this->renderForm('commentaire/admin/admin_edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    
    #[Route('/{id}', name: 'app_commentaire_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        // Get the associated article for the comment
        $article = $commentaire->getArticle();
    
        // Check if the CSRF token is valid
        // if ($this->isCsrfTokenValid('delete' . $commentaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
    
            // Decrement nbComments in the associated article
            $article->setNbComments($article->getNbComments() - 1);
            $entityManager->persist($article);
            $entityManager->flush();
    
            // Return a JSON response indicating success
            // return $this->redirectToRoute('app_article_show', ['id' => $article->getId()]);

            return new RedirectResponse($this->generateUrl('app_article_show', ['id' => $article->getId()]));
        

    }




    
    #[Route('/admin/{id}', name: 'app_commentaire_admin_delete', methods: ['POST'])]
    public function admin_delete(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commentaire->getId(), $request->request->get('_token'))) {
             $article = $commentaire->getArticle();
    
        // Check if the CSRF token is valid
        // if ($this->isCsrfTokenValid('delete' . $commentaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
    
            // Decrement nbComments in the associated article
            $article->setNbComments($article->getNbComments() - 1);
            $entityManager->persist($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_article_admin_show', ['id' => $article->getId()]);
    }
}
