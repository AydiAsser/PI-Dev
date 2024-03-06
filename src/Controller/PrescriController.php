<?php

namespace App\Controller;

use App\Entity\Prescri;
use App\Form\PrescriType;
use App\Repository\PrescriRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/prescri')]
class PrescriController extends AbstractController
{
    #[Route('/', name: 'app_prescri_index', methods: ['GET'])]
    public function index(PrescriRepository $prescriRepository): Response
    {
        return $this->render('prescri/index.html.twig', [
            'prescris' => $prescriRepository->findAll(),
        ]);
    }
   


    #[Route('/2', name: 'app_prescri_indexdzd', methods: ['GET'])]
    public function indexdzsa(PrescriRepository $prescriRepository): Response
    {
        return $this->render('prescri/frontPre.html.twig', [
            'prescris' => $prescriRepository->findAll(),
        ]);
    }



    #[Route('/new1', name: 'app_presdcri_new', methods: ['GET', 'POST'])]
    public function nedw(Request $request, EntityManagerInterface $entityManager): Response
    {
        $prescri = new Prescri();
        $form = $this->createForm(PrescriType::class, $prescri);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($prescri);
            $entityManager->flush();

            return $this->redirectToRoute('app_prescri_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('prescri/frontPre.html.twig', [
            'prescri' => $prescri,
            'form' => $form,
        ]);
    }


    #[Route('/1', name: 'app_prescri_indexda', methods: ['GET'])]
    public function indexdz(PrescriRepository $prescriRepository): Response
    {
        return $this->render('prescri/backpre.html.twig', [
            'prescris' => $prescriRepository->findAll(),
        ]);
    }


    #[Route('/new', name: 'app_prescri_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $prescri = new Prescri();
        $form = $this->createForm(PrescriType::class, $prescri);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($prescri);
            $entityManager->flush();

            return $this->redirectToRoute('app_prescri_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('prescri/new.html.twig', [
            'prescri' => $prescri,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_prescri_show', methods: ['GET'])]
    public function show(Prescri $prescri): Response
    {
        return $this->render('prescri/show.html.twig', [
            'prescri' => $prescri,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_prescri_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Prescri $prescri, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PrescriType::class, $prescri);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_prescri_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('prescri/edit.html.twig', [
            'prescri' => $prescri,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_prescri_delete', methods: ['POST'])]
    public function delete(Request $request, Prescri $prescri, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$prescri->getId(), $request->request->get('_token'))) {
            $entityManager->remove($prescri);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_prescri_index', [], Response::HTTP_SEE_OTHER);
    }
}
