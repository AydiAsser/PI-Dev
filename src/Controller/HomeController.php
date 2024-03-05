<?php

namespace App\Controller;


use App\Repository\UserRepository;
use App\Repository\CalendarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
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

    #[Route('/localisation', name: 'appd_home2')]
    public function index15(): Response
    {
        return $this->render('localisation.html.twig', [
            'controller_name' => 'HomeController',
        ]);

    }
    
    #[Route('/rdv', name: 'app_home1')]
    public function index3(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

 




    #[route('/test/{id}',name:'test')]
   public function test($id)
   {
      
return $this->render('home/home.html.twig', ['param'=>$id]);

   }

}
