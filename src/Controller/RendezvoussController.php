<?php

namespace App\Controller;

use App\Entity\Rendezvouss;
use App\Form\RendezvoussType;
use App\Repository\RendezvoussRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rendezvouss')]
class RendezvoussController extends AbstractController
{
    #[Route('/', name: 'app_rendezvouss_index', methods: ['GET'])]
    public function index(RendezvoussRepository $rendezvoussRepository): Response
    {
        return $this->render('rendezvouss/index.html.twig', [
            'rendezvousses' => $rendezvoussRepository->findAll(),
        ]);
    }



    #[Route('/afficher', name: 'app_rendezvouss_index12', methods: ['GET'])]
    public function index12(RendezvoussRepository $rendezvoussRepository): Response
    {
        return $this->render('rendezvouss/frontR.html.twig', [
            'rendezvouss' => $rendezvoussRepository->findAll(),
        ]);
    }


    #[Route('/r', name: 'app_rendezvouss_index122', methods: ['GET'])]
    public function index122(RendezvoussRepository $rendezvoussRepository): Response
    {
        return $this->render('rendezvouss/frontP.html.twig', [
            'rendezvouss' => $rendezvoussRepository->findAll(),
        ]);
    }



    #[Route('/reserver', name: 'app_rendezvouss_new1', methods: ['GET', 'POST'])]
    public function new1(Request $request, EntityManagerInterface $entityManager): Response
    {
        $rendezvouss = new Rendezvouss();
        $form = $this->createForm(RendezvoussType::class, $rendezvouss);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($rendezvouss);
            $entityManager->flush();
            flash()->addSuccess('Votre Reservation est supprimer avec succés');
            return $this->redirectToRoute('app_home3', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rendezvouss/frontP.html.twig', [
            'rendezvouss' => $rendezvouss,
            'form' => $form,
        ]);
    }






    #[Route('/new', name: 'app_rendezvouss_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $rendezvouss = new Rendezvouss();
        $form = $this->createForm(RendezvoussType::class, $rendezvouss);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($rendezvouss);
            $entityManager->flush();
           
            flash()->addSuccess('Votre Reservation est supprimer avec succés');
            return $this->redirectToRoute('app_home3', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rendezvouss/new.html.twig', [
            'rendezvouss' => $rendezvouss,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rendezvouss_show', methods: ['GET'])]
    public function show(Rendezvouss $rendezvouss): Response
    {
        return $this->render('rendezvouss/show.html.twig', [
            'rendezvouss' => $rendezvouss,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_rendezvouss_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Rendezvouss $rendezvouss, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RendezvoussType::class, $rendezvouss);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_rendezvouss_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rendezvouss/edit.html.twig', [
            'rendezvouss' => $rendezvouss,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rendezvouss_delete', methods: ['POST'])]
    public function delete(Request $request, Rendezvouss $rendezvouss, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rendezvouss->getId(), $request->request->get('_token'))) {
            $entityManager->remove($rendezvouss);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_rendezvouss_index', [], Response::HTTP_SEE_OTHER);
    }










}
