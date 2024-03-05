<?php

namespace App\Controller;
use App\Entity\User;
use App\Entity\Panier;
use App\Entity\Medicament;
use App\Form\PanierType;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;

#[Route('/panier')]
class PanierController extends AbstractController
{
    #[Route('/', name: 'app_panier_index', methods: ['GET'])]
    public function index(PanierRepository $panierRepository): Response
    {
        return $this->render('panier/index.html.twig', [
            'paniers' => $panierRepository->findAll(),
        ]);
    }



    #[Route('/panier', name: 'app_panidzaer_index', methods: ['GET'])]
    public function index15(PanierRepository $panierRepository): Response
    {
        return $this->render('panier/panier1.html.twig', [
            'paniers' => $panierRepository->findAll(),
        ]);
    }

    
    
    #[Route('/add-to-cart/{id}', name: 'app_add_to_cart', methods: ['POST'])]
public function addToCart(Request $request, Medicament $medicament, EntityManagerInterface $entityManager): Response
{
    $client = $this->getDoctrine()->getRepository(User::class)->find(1); // Récupérer le client avec l'ID 1

    if (!$client) {
        return new JsonResponse(['message' => 'Default client not found'], 404);
    }

    $panier = new Panier();
    $panier->setClient($client);
    $panier->setMedi($medicament);
    $panier->setNom($medicament->getNom());
    $panier->setQuantite(1);
    $panier->setImage($medicament->getImage());
    $panier->setPrix($medicament->getPrix());
    $panier->setDescription($medicament->getDescription());
    $panier->setCategorie($medicament->getCategorie());
    flash()->addSuccess('medicament ajouter au panier ');
    $entityManager->persist($panier);
    $entityManager->flush();

    return $this->redirectToRoute('app_medicament_index', [], Response::HTTP_SEE_OTHER);
}
    
    
    

     





    
    
    
    
    
    
    
    #[Route('/new', name: 'app_panier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $panier = new Panier();
        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($panier);
            $entityManager->flush();

            return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('panier/new.html.twig', [
            'panier' => $panier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_panier_show', methods: ['GET'])]
    public function show(Panier $panier): Response
    {
        return $this->render('panier/show.html.twig', [
            'panier' => $panier,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_panier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('panier/edit.html.twig', [
            'panier' => $panier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_panier_delete', methods: ['POST'])]
    public function delete(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$panier->getId(), $request->request->get('_token'))) {
            $entityManager->remove($panier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_panidzaer_index', [], Response::HTTP_SEE_OTHER);
    }
}
