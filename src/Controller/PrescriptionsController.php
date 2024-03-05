<?php

namespace App\Controller;

use App\Entity\Prescriptions;
use App\Form\PrescriptionsType;
use App\Repository\PrescriptionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/prescriptions')]
class PrescriptionsController extends AbstractController
{
    #[Route('/', name: 'app_prescriptions_index', methods: ['GET'])]
    public function index(PrescriptionsRepository $prescriptionsRepository): Response
    {
        return $this->render('prescriptions/index.html.twig', [
            'prescriptions' => $prescriptionsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_prescriptions_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $prescription = new Prescriptions();
        $form = $this->createForm(PrescriptionsType::class, $prescription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($prescription);
            $entityManager->flush();

            return $this->redirectToRoute('app_prescriptions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('prescriptions/new.html.twig', [
            'prescription' => $prescription,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_prescriptions_show', methods: ['GET'])]
    public function show(Prescriptions $prescription): Response
    {
        return $this->render('prescriptions/show.html.twig', [
            'prescription' => $prescription,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_prescriptions_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Prescriptions $prescription, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PrescriptionsType::class, $prescription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_prescriptions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('prescriptions/edit.html.twig', [
            'prescription' => $prescription,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_prescriptions_delete', methods: ['POST'])]
    public function delete(Request $request, Prescriptions $prescription, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$prescription->getId(), $request->request->get('_token'))) {
            $entityManager->remove($prescription);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_prescriptions_index', [], Response::HTTP_SEE_OTHER);
    }
}
