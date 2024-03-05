<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Form\CalendarType;
use App\Repository\CalendarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormError;


#[Route('/calendar')]
class CalendarController extends AbstractController
{
    #[Route('/', name: 'app_calendar_index', methods: ['GET'])]
    public function index(CalendarRepository $calendarRepository): Response
    {


        flash()->addSuccess('bienvenu dans notre plateforme');
        return $this->render('calendar/index.html.twig', [
            'calendars' => $calendarRepository->findAll(),
        ]);
    }


    #[Route('/back', name: 'app_calendar_indexd', methods: ['GET'])]
    public function index1(CalendarRepository $calendarRepository): Response
    {


        flash()->addSuccess('bienvenu dans notre partie back ');
        return $this->render('calendar/backcal.html.twig', [
            'calendars' => $calendarRepository->findAll(),
        ]);
    }




    #[Route('/search_cal', name: 'cal')]
    public function searchrdv(Request $request, EntityManagerInterface $entityManager,): Response
    {
        $searchQuery = $request->query->get('search_query');

        $cal = $entityManager->getRepository(Calendar::class)
            ->createQueryBuilder('u')
            ->where('u.title LIKE :query')
            ->orWhere('u.description LIKE :query')
            ->setParameter('query', '%' . $searchQuery . '%')
            ->getQuery()
            ->getResult();

            flash()->addSuccess('resultat trouve');
        return $this->render('calendar/index.html.twig', [
            'calendars' => $cal,
           
        ]);
    }








    #[Route('/new', name: 'app_calendar_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $calendar = new Calendar();
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);
        flash()->addSuccess('medecin selectionné');
        if ($form->isSubmitted() && $form->isValid()) {
           
            $overlapping = $entityManager->getRepository(Calendar::class)
            ->createQueryBuilder('c')
            ->where('c.start < :end AND c.end > :start AND c.user = :user')
            ->setParameter('start', $calendar->getStart())
            ->setParameter('end', $calendar->getEnd())
            ->setParameter('user', $calendar->getUser())
            ->getQuery()
            ->getResult();
            if ($overlapping) {
                $form->addError(new FormError('Désolé, ce rendez vous est réservée pour cette  date. Veuillez choisir une autre date ou changer la date.'));
            } else {
                $entityManager->persist($calendar);
                $entityManager->flush();
                flash()->addSuccess('Votre Reservation est cree avec succés');
                return $this->redirectToRoute('app_calendar_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->renderForm('calendar/frontC.html.twig', [
            'calendar' => $calendar,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_calendar_show', methods: ['GET'])]
    public function show(Calendar $calendar): Response
    {
        return $this->render('calendar/show.html.twig', [
            'calendar' => $calendar,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_calendar_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Calendar $calendar, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_calendar_indexd', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('calendar/edit.html.twig', [
            'calendar' => $calendar,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_calendar_delete', methods: ['POST'])]
    public function delete(Request $request, Calendar $calendar, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$calendar->getId(), $request->request->get('_token'))) {
            $entityManager->remove($calendar);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_calendar_indexd', [], Response::HTTP_SEE_OTHER);
    }
}
