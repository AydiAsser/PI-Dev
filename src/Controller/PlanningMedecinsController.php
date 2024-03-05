<?php

namespace App\Controller;

use App\Entity\PlanningMedecins;
use App\Form\PlanningMedecinsType;
use App\Repository\PlanningMedecinsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/p')]
class PlanningMedecinsController extends AbstractController
{
    #[Route('/', name: 'app_planning_medecins_index', methods: ['GET'])]
    public function index(PlanningMedecinsRepository $planningMedecinsRepository): Response
    {
        return $this->render('planning_medecins/index.html.twig', [
            'planning_medecins' => $planningMedecinsRepository->findAll(),
        ]);
    }

    #[Route('/back', name: 'app_planning_medecins_indedxdza', methods: ['GET'])]
    public function index21(PlanningMedecinsRepository $planningMedecinsRepository): Response
    {
        return $this->render('planning_medecins/backplan.html.twig', [
            'planning_medecins' => $planningMedecinsRepository->findAll(),
        ]);
    }


    #[Route('/affiche', name: 'app_planning_medecins_index1', methods: ['GET'])]
    public function index1(PlanningMedecinsRepository $planningMedecinsRepository): Response
    {
        return $this->render('planning_medecins/Pfront.html.twig', [
            'p' => $planningMedecinsRepository->findAll(),
        ]);
    }






    #[Route('/new', name: 'app_planning_medecins_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $planningMedecin = new PlanningMedecins();
        $form = $this->createForm(PlanningMedecinsType::class, $planningMedecin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($planningMedecin);
            $entityManager->flush();

            return $this->redirectToRoute('app_planning_medecins_indedxdza', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('planning_medecins/new.html.twig', [
            'planning_medecin' => $planningMedecin,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_planning_medecins_show', methods: ['GET'])]
    public function show(PlanningMedecins $planningMedecin): Response
    {
        return $this->render('planning_medecins/show.html.twig', [
            'planning_medecin' => $planningMedecin,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_planning_medecins_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PlanningMedecins $planningMedecin, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlanningMedecinsType::class, $planningMedecin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_planning_medecins_indedxdza', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('planning_medecins/edit.html.twig', [
            'planning_medecin' => $planningMedecin,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_planning_medecins_delete', methods: ['POST'])]
    public function delete(Request $request, PlanningMedecins $planningMedecin, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$planningMedecin->getId(), $request->request->get('_token'))) {
            $entityManager->remove($planningMedecin);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_planning_medecins_indedxdza', [], Response::HTTP_SEE_OTHER);
    }
}
