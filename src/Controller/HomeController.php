<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MedicamentRepository;
use App\Repository\RendezvoussRepository;
use App\Repository\CalendarRepository;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home2')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }




    #[Route('/stats', name: 'app_stats')]
    public function statistiquess(CalendarRepository $transprepo)
    {
        $transprepo = $transprepo->findAll();

        $transpId = [];
        
        foreach( $transprepo as $transport_reservations){
            $transpId[] = $transport_reservations->getUser()->getFirstName();
            $occurrences = array_count_values($transpId);
        }


        return $this->render('stat.html.twig', [
            'transpId' => json_encode($transpId),
            'transpIdCount' => json_encode($occurrences),
        ]);
    }




    #[Route('/statsMedi', name: 'app_statss')]
    public function statistiquesss(MedicamentRepository $transprepo)
    {
        $transprepo = $transprepo->findAll();

        $transpId = [];
        
        foreach( $transprepo as $transport_reservations){
            $transpId[] = $transport_reservations->getNom();
            $occurrences = array_count_values($transpId);
        }


        return $this->render('statMedi.html.twig', [
            'transpId' => json_encode($transpId),
            'transpIdCount' => json_encode($occurrences),
        ]);
    }





    #[Route('/localisation', name: 'appd_home2')]
    public function index15(): Response
    {
        return $this->render('localisation.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }



    #[Route('/front', name: 'app_home3')]
    public function index2(MedicamentRepository $medicamentRepository,RendezvoussRepository $rendezvoussRepository ): Response
    {
        return $this->render('front.html.twig', [
            'controller_name' => 'HomeController',
            // 'medicaments' => $medicamentRepository->findAll(),
            'rendezvouss' => $rendezvoussRepository->findAll(),

        ]);
    }

    #[Route('/rdv', name: 'app_home1')]
    public function index3(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/back', name: 'app_home')]
    public function index1(): Response
    {
        return $this->render('backk.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }




    #[route('/test/{id}',name:'test')]
   public function test($id)
   {
      
return $this->render('home/home.html.twig', ['param'=>$id]);

   }

    
}
