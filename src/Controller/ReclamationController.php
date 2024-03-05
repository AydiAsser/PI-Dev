<?php


namespace App\Controller;
use App\Entity\Reclamation;
use App\Form\Reclamation1Type;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


#[Route('/reclamation')]
class ReclamationController extends AbstractController
{

    #[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        // Récupération des réclamations
        $reclamations = $reclamationRepository->findAll();
       
        // Rendu de la vue avec les réclamations
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }


    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
   
    public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $reclamation = new Reclamation();
    $reclamation->setDateCreation(new \DateTime());


    $form = $this->createForm(Reclamation1Type::class, $reclamation);
    $form->handleRequest($request);


    if ($form->isSubmitted() && $form->isValid()) {
        $reclamationSujet = $reclamation->getSujet();
        $censoredReclamationSujet= $this->censorReclamation($reclamationSujet);
        $reclamation->setSujet($censoredReclamationSujet);
       
        $reclamationDescription = $reclamation->getDescription();
        $censoredReclamationDescription= $this->censorReclamation($reclamationDescription);
        $reclamation->setDescription($censoredReclamationDescription);
       
       
       
       
        // Gestion de l'envoi de l'image
        /** @var UploadedFile $imageFile */
        $imageFile = $form->get('imageFile')->getData();


        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            // ceci est nécessaire pour inclure en toute sécurité le nom du fichier comme une partie de l'URL
            $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();


            // Déplacez le fichier vers le répertoire où les images sont stockées
            try {
                $imageFile->move(
                    $this->getParameter('pictures_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... gérer l'exception si quelque chose ne va pas lors du déplacement du fichier
            }


            // met à jour le nom du fichier dans la base de données
            $reclamation->setImageFile($newFilename);
        }


        $entityManager->persist($reclamation);
        $entityManager->flush();


        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }


    return $this->renderForm('reclamation/new.html.twig', [
        'reclamation' => $reclamation,
        'form' => $form,
    ]);
}




#[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation,MailerInterface $mailer , EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Reclamation1Type::class, $reclamation);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $reclamationSujet = $reclamation->getSujet();
            $censoredReclamationSujet= $this->censorReclamation($reclamationSujet);
            $reclamation->setSujet($censoredReclamationSujet);
           
            $reclamationDescription = $reclamation->getDescription();
            $censoredReclamationDescription= $this->censorReclamation($reclamationDescription);
            $reclamation->setDescription($censoredReclamationDescription);
           
            $entityManager->flush();


            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }


        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route("/reclamations/imprimer-pdf", name:"reclamations_imprimer_pdf")]
     public function imprimerPDF(): Response
   {
       // Récupérer les données des réclamations (par exemple, depuis une base de données)
       $reclamations = $this->getDoctrine()->getRepository(Reclamation::class)->findAll();
   
       // Génération du contenu HTML à partir d'une vue Twig
       $html = $this->renderView('reclamation/pdf.html.twig', [
           'reclamations' => $reclamations,
       ]);
   
       // Configuration de Dompdf
       $options = new Options();
       $options->set('isHtml5ParserEnabled', true);
   
       // Initialisation de Dompdf
       $dompdf = new Dompdf($options);
   
       // Chargement du contenu HTML dans Dompdf
       $dompdf->loadHtml($html);
   
       // Réglage du format du papier et de l'orientation
       $dompdf->setPaper('A4', 'portrait');
   
       // Rendu du PDF
       $dompdf->render();
   
       // Renvoyer une réponse de téléchargement avec le contenu du PDF
       $response = new Response($dompdf->output());
       $disposition = $response->headers->makeDisposition(
           ResponseHeaderBag::DISPOSITION_ATTACHMENT,
           'liste_reclamations.pdf'
       );
       $response->headers->set('Content-Disposition', $disposition);
   
       return $response;
   }
   








   #[Route("/reclamations/statistiques", name:"reclamations_statistiques")]


  public function statistiques(ReclamationRepository $reclamationRepository): Response
  {
      $reclamations = $reclamationRepository->findAll();


      $stats = [];


      foreach ($reclamations as $reclamation) {
          $date = $reclamation->getDateCreation()->format('Y-m-d');


          if (!isset($stats[$date])) {
              $stats[$date] = 1;
          } else {
              $stats[$date]++;
          }
      }


      return $this->render('reclamation/statistiques.html.twig', [
          'stats' => $stats,
      ]);
  }


















      private $forbiddenWords = ['tla', 'proba', 'micro'];
 
      #[Route("/submit-reclamation", name:"submit_reclamation", methods: ['POST'])]
      public function submitReclamation(Request $request): Response
      {
          $reclamation = $request->request->get('reclamation');
 
          // Censure des mots interdits
          $censoredReclamation = $this->censorReclamation($reclamation);
 
          // Traiter la réclamation normalement ou afficher une erreur si nécessaire
          if ($censoredReclamation !== $reclamation) {
              // Rediriger vers la page de création de réclamation avec un message d'erreur
              return $this->redirectToRoute('app_reclamation_new', ['error' => 'La réclamation contient des mots interdits']);
          }
 
          // Traiter la réclamation normalement si elle est valide
         
          // Rediriger vers la page d'accueil ou où vous le souhaitez
          return $this->redirectToRoute('homepage');
      }
 
      /**
       * Censure les mots interdits dans une réclamation.
       */
      private function censorReclamation(string $reclamation): string
      {
          foreach ($this->forbiddenWords as $word) {
              if (stripos($reclamation, $word) !== false) {
                  $reclamation = str_ireplace($word, str_repeat('*', mb_strlen($word)), $reclamation);
              }
          }
          return $reclamation;
      }
     












    //   #[Route("/search", name:"search", methods: ['POST'])]
      #[Route('/search', name: 'app_reclamation_search', methods: ['POST'])]
      public function searchByDescription(Request $request, ReclamationRepository $reclamationRepository)
      {
          $description = $request->query->get('description');
          $reclamations = $reclamationRepository->findByDescription($description);
     
          // Convertir les résultats en format JSON
          $data = [];
          foreach ($reclamations as $reclamation) {
              $data[] = [
                  'id' => $reclamation->getId(),
                  'sujet' => $reclamation->getSujet(),
                  'date' => $reclamation->getdateCreation(),
                  'dateCreation' => $reclamation->getDateCreation()->format('Y-m-d H:i:s')


                  // Ajoutez d'autres champs selon vos besoins
              ];
          }
     
          // Renvoyer une réponse JSON
          return new JsonResponse($data);
      }
     }
