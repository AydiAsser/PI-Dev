<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Article;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use App\Repository\UserRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Service\ForbiddenWordChecker;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


#[Route('/commentaire')]
class CommentaireController extends AbstractController
{

    private $forbiddenWordChecker;

    public function __construct(ForbiddenWordChecker $forbiddenWordChecker)
    {
        $this->forbiddenWordChecker = $forbiddenWordChecker;
    }




    #[Route('/', name: 'app_commentaire_index', methods: ['GET'])]
    public function index(SessionInterface $session, UserRepository $repository,CommentaireRepository $commentaireRepository): Response
    {
        $myValue = $session->get('my_key')->getId();
        $u=$repository->find($myValue);

        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
            'user'=>$u
        ]);
    }

    #[Route('/admin', name: 'app_commentaire_admin_index', methods: ['GET'])]
    public function admin_index(SessionInterface $session, UserRepository $repository,CommentaireRepository $commentaireRepository): Response
    {
        $myValue = $session->get('my_key')->getId();
        $u=$repository->find($myValue);

        return $this->render('commentaire/admin/admin_index.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
            'users'=>$u,
        ]);
    }

    #[Route('/new', name: 'app_commentaire_new', methods: ['POST'])]
    public function new(SessionInterface $session, UserRepository $repository,Request $request, EntityManagerInterface $entityManager): Response
    {
        $myValue = $session->get('my_key')->getId();
        $u=$repository->find($myValue);
        
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
        $commentaire->setCommenter($u);
    
        // Associate the comment with the article
        $commentaire->setArticle($article);
    
        $article->setNbComments($article->getNbComments() + 1);
    
        // Persist the comment to the database
        $entityManager->persist($commentaire);
        $entityManager->flush();
    }

// Redirect the user to the appropriate page
return $this->redirectToRoute('app_article_show', ['id' => $articleId , 'user'=>$u]);

    }
    

    #[Route('/admin/new', name: 'app_commentaire_admin_new', methods: ['GET', 'POST'])]
    public function admin_new(SessionInterface $session, UserRepository $repository,Request $request, EntityManagerInterface $entityManager): Response
    {
        $myValue = $session->get('my_key')->getId();
        $u=$repository->find($myValue);

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
            'user'=> $u
        ]);
    }


    #[Route('/{id}', name: 'app_commentaire_show', methods: ['GET'])]
    public function show(SessionInterface $session, UserRepository $repository,Commentaire $commentaire): Response
    {
        $myValue = $session->get('my_key')->getId();
        $u=$repository->find($myValue);

        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,
            'user'=>$u
        ]);
    }

    #[Route('/admin/{id}', name: 'app_commentaire_admin_show', methods: ['GET'])]
    public function admin_show(SessionInterface $session, UserRepository $repository,Commentaire $commentaire): Response
    {
        $myValue = $session->get('my_key')->getId();
        $u=$repository->find($myValue);

        return $this->render('commentaire/admin/admin_show.html.twig', [
            'commentaire' => $commentaire,
            'user'=> $u
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commentaire_edit')]
    public function edit(SessionInterface $session, UserRepository $repository,Request $request, Commentaire $commentaire): Response
    {
        $myValue = $session->get('my_key')->getId();
        $u=$repository->find($myValue);

        $entityManager = $this->getDoctrine()->getManager();
        $updatedComment = $request->request->get('contenu');

        $commentaire->setContenu($updatedComment);
        $entityManager->flush();

        return new Response('Comment updated successfully', 200);
    }





    #[Route('/admin/{id}/edit', name: 'app_commentaire_admin_edit', methods: ['GET', 'POST'])]
    public function admin_edit(SessionInterface $session, UserRepository $repository,Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $myValue = $session->get('my_key')->getId();
        $u=$repository->find($myValue);

        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_article_admin_show', ['id' => $commentaire->getArticle()->getId()]);

        }

        return $this->renderForm('commentaire/admin/admin_edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
            'user'=> $u
        ]);
    }

    
    #[Route('/{id}', name: 'app_commentaire_delete', methods: ['POST'])]
    public function delete(SessionInterface $session, UserRepository $repository,Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $myValue = $session->get('my_key')->getId();
        $u=$repository->find($myValue);

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

            return new RedirectResponse($this->generateUrl('app_article_show', ['id' => $article->getId() , 'user'=>$u]));
        

    }




    
    #[Route('/admin/{id}', name: 'app_commentaire_admin_delete', methods: ['POST'])]
    public function admin_delete(SessionInterface $session, UserRepository $repository,Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $myValue = $session->get('my_key')->getId();
        $u=$repository->find($myValue);

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

        return $this->redirectToRoute('app_article_admin_show', ['id' => $article->getId(),'user'=>$u]);
    }
}
