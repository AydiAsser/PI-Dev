<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\ProfileType;
use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\UserRepository;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\HttpFoundation\Request;


#[Route('/H')]
class HController extends AbstractController
{
    #[Route('/', name: 'app_H', methods: ['GET'])]
    public function index(UserRepository $x): Response
    {

        $formations = $x->findAll();

        return $this->render('TestUser/index.html.twig', [
            'users' => $formations,
        ]);
    }

 }