<?php

namespace App\Controller;

use App\Entity\Medicament;
use App\Form\MedicamentType;
use App\Repository\MedicamentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/medicament')]
class MedicamentController extends AbstractController
{
    
    #[Route('/search_medicamentt', name: 'medicament')]
    public function searchmedi(Request $request, EntityManagerInterface $entityManager,MedicamentRepository $medicamentRepository): Response
    {
        $searchQuery = $request->query->get('search_query');

        $medi = $entityManager->getRepository(Medicament::class)
            ->createQueryBuilder('u')
            ->where('u.nom LIKE :query')
            ->orWhere('u.categorie LIKE :query')
            ->setParameter('query', '%' . $searchQuery . '%')
            ->getQuery()
            ->getResult();

        return $this->render('medicament/index.html.twig', [
            'medicaments' => $medi,
           
        ]);
    }
  
  

    
    #[Route('/search_medicamentA', name: 'medicamentA')]
    public function searchmediA(Request $request, EntityManagerInterface $entityManager,MedicamentRepository $medicamentRepository): Response
    {
        $searchQuery = 'antidepresseur';

        $medi = $entityManager->getRepository(Medicament::class)
            ->createQueryBuilder('u')
            ->where('u.nom LIKE :query')
            ->orWhere('u.categorie LIKE :query')
            ->setParameter('query', '%' . $searchQuery . '%')
            ->getQuery()
            ->getResult();

        return $this->render('medicament/index.html.twig', [
            'medicaments' => $medi,
           
        ]);
    }



    #[Route('/search_medicamentB', name: 'medicamentB')]
    public function searchmediB(Request $request, EntityManagerInterface $entityManager,MedicamentRepository $medicamentRepository): Response
    {
        $searchQuery = 'antibiotique';

        $medi = $entityManager->getRepository(Medicament::class)
            ->createQueryBuilder('u')
            ->where('u.nom LIKE :query')
            ->orWhere('u.categorie LIKE :query')
            ->setParameter('query', '%' . $searchQuery . '%')
            ->getQuery()
            ->getResult();

        return $this->render('medicament/index.html.twig', [
            'medicaments' => $medi,
           
        ]);
    }
  

    #[Route('/search_medicamentC', name: 'medicamentC')]
    public function searchmediC(Request $request, EntityManagerInterface $entityManager,MedicamentRepository $medicamentRepository): Response
    {
        $searchQuery = 'paracetamole';

        $medi = $entityManager->getRepository(Medicament::class)
            ->createQueryBuilder('u')
            ->where('u.nom LIKE :query')
            ->orWhere('u.categorie LIKE :query')
            ->setParameter('query', '%' . $searchQuery . '%')
            ->getQuery()
            ->getResult();

        return $this->render('medicament/index.html.twig', [
            'medicaments' => $medi,
           
        ]);
    }
  


    #[Route('/search_medicamentD', name: 'medicamentD')]
    public function searchmediD(Request $request, EntityManagerInterface $entityManager,MedicamentRepository $medicamentRepository): Response
    {
        $searchQuery = 'analgesique';

        $medi = $entityManager->getRepository(Medicament::class)
            ->createQueryBuilder('u')
            ->where('u.nom LIKE :query')
            ->orWhere('u.categorie LIKE :query')
            ->setParameter('query', '%' . $searchQuery . '%')
            ->getQuery()
            ->getResult();

        return $this->render('medicament/index.html.twig', [
            'medicaments' => $medi,
           
        ]);
    }
  
  
    #[Route('/', name: 'app_medicament_index', methods: ['GET'])]
    public function index(MedicamentRepository $medicamentRepository): Response
    {
        return $this->render('medicament/index.html.twig', [
            'medicaments' => $medicamentRepository->findAll(),
        ]);
    }



    #[Route('/panier', name: 'app_medicaments_index', methods: ['GET'])]
    public function indexa(MedicamentRepository $medicamentRepository): Response
    {
        return $this->render('medicament/panier.html.twig', [
            'medicaments' => $medicamentRepository->findAll(),
        ]);
    }



    #[Route('/b', name: 'app_medicament_ind3ex', methods: ['GET'])]
    public function index2(MedicamentRepository $medicamentRepository): Response
    {
        return $this->render('medicament/backM.html.twig', [
            'm' => $medicamentRepository->findAll(),
        ]);
    }


    #[Route('/{id}/fr', name: 'app_medicament_inde2x', methods: ['GET'])]
    public function index24(MedicamentRepository $medicamentRepository): Response
    {
        return $this->render('medicament/shopD.html.twig', [
            'medicaments' => $medicamentRepository->findAll(),
        ]);
    }





    #[Route('/new', name: 'app_medicament_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,string $photoDir = 'public/assets/images/explore'): Response
    {
        $medicament = new Medicament();
        $form = $this->createForm(MedicamentType::class, $medicament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
           
           
            $medicament = $form->getData();
            
            if ($photo = $form['image']->getData()) {
                $fileName = uniqid() . '.' . $photo->guessExtension();
                $photo->move($photoDir, $fileName);
            }
            
            $medicament->setImage($fileName);
           
           
           
           
            $entityManager->persist($medicament);
            $entityManager->flush();

            return $this->redirectToRoute('app_medicament_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('medicament/new.html.twig', [
            'medicament' => $medicament,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_medicament_show', methods: ['GET'])]
    public function show(Medicament $medicament): Response
    {
        return $this->render('medicament/show.html.twig', [
            'medicament' => $medicament,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_medicament_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Medicament $medicament, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MedicamentType::class, $medicament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_medicament_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('medicament/edit.html.twig', [
            'medicament' => $medicament,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_medicament_delete', methods: ['POST'])]
    public function delete(Request $request, Medicament $medicament, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$medicament->getId(), $request->request->get('_token'))) {
            $entityManager->remove($medicament);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_medicament_index', [], Response::HTTP_SEE_OTHER);
    }
}
