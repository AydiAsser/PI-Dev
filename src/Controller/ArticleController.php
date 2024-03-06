<?php

namespace App\Controller;
use App\Entity\Article;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\CommentaireRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Mime\MimeTypes;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpClient\HttpClient;
use MYPDF;
use TCPDF;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


#[Route('/article')]
class ArticleController extends AbstractController
{

    #[Route('/like/{idArticle}', name: 'app_article_like', methods: ['POST'])]
    public function likeArticle(SessionInterface $session, UserRepository $repository,$idArticle, Request $request, EntityManagerInterface $entityManager, ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->find($idArticle);

        $myValue = $session->get('my_key');
        if (!$myValue) {
            throw new \Exception('Session key not found.');
        }

        $u = $repository->find($myValue->getId());
        if (!$u) {
            throw new \Exception('User not found.');
        }

        if (!$article) {
            throw new \Exception('Article not found.');
        }

        $userID = $u->getId();

        if (!$article) {
            return new Response('Article not found', Response::HTTP_NOT_FOUND);
        }

        // Get the list of likes
        $likesList = $article->getLikesList();

        // Ensure $likesList is initialized as an array
        $likesList = $likesList ?? [];

        // // Get the client IP address
        // $clientIP = $request->getClientIp();

        if (in_array($userID, $likesList)) {
            $likesList = array_diff($likesList, array($userID));
            $article->setLikesList($likesList);
            $article->setNbLikes($article->getNbLikes() - 1);
        } else {
            $likesList[] = $userID;
            $article->setLikesList($likesList);
            $article->setNbLikes($article->getNbLikes() + 1);
        }

        $entityManager->flush();

        // Return the updated like count as JSON response
        $response = [
            'likes' => $article->getNbLikes()
        ];

        return $this->json($response);
    }
    

    #[Route('/admin', name: 'app_article_admin_index', methods: ['GET'])]
    public function admin_index(SessionInterface $session, UserRepository $repository,ArticleRepository $articleRepository): Response
    {
        $myValue = $session->get('my_key')->getId();
        $u=$repository->find($myValue);

        return $this->render('article/admin/admin_index.html.twig', [
            'articles' => $articleRepository->findAll(),
            'user' => $u
        ]);
    }


    #[Route('/admin/new', name: 'app_article_admin_new', methods: ['GET', 'POST'])]
    public function admin_new(SessionInterface $session, UserRepository $repository,Request $request, EntityManagerInterface $entityManager): Response
    {
        $myValue = $session->get('my_key')->getId();
        $u=$repository->find($myValue);

        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle picture file upload
            $pictureFile = $form->get('pictureFile')->getData();

            if ($pictureFile) {
                // Generate a unique name for the picture file
                $newFilename = uniqid() . '.' . $pictureFile->guessExtension();

                // Move the file to the desired directory
                try {
                    $pictureFile->move(
                        $this->getParameter('pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload error
                }

                // Update the picture property to store the file name
                $article->setPicture($newFilename);
            }

            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/admin/admin_new.html.twig', [
            'article' => $article,
            'form' => $form,
            'user'=> $u,
        ]);
    }


    #[Route('/admin/{id}', name: 'app_article_admin_show', methods: ['GET'])]
    public function admin_show(SessionInterface $session, UserRepository $repository,CommentaireRepository $commentaireRepository, Article $article): Response
    {
        $myValue = $session->get('my_key')->getId();
        $u=$repository->find($myValue);

        $articleId = $article->getId();
        $commentaires = $commentaireRepository->findByArticleId($articleId);

        return $this->render('article/admin/admin_show.html.twig', [
            'article' => $article,
            'commentaires' => $commentaires,
            'user'=> $u,
        ]);
    }




    #[Route('/admin/{id}/edit', name: 'app_article_admin_edit', methods: ['GET', 'POST'])]
    public function admin_edit(SessionInterface $session, UserRepository $repository,Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $myValue = $session->get('my_key')->getId();
        $u=$repository->find($myValue);

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

              /** @var UploadedFile $pictureFile */
              $pictureFile = $form->get('pictureFile')->getData();
              if ($pictureFile) {
                  $newFilename = uniqid() . '.' . $pictureFile->guessExtension();
                  try {
                      $pictureFile->move(
                          $this->getParameter('pictures_directory'),
                          $newFilename
                      );
                      $article->setPicture($newFilename);
                  } catch (FileException $e) {
                      // Handle file upload error
                  }
              }
              
            $entityManager->flush();

            return $this->redirectToRoute('app_article_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/admin/admin_edit.html.twig', [
            'article' => $article,
            'form' => $form,
            'user' => $u,
        ]);
    }

    #[Route('/admin/{id}', name: 'app_article_admin_delete', methods: ['POST'])]
    public function admin_delete(SessionInterface $session, UserRepository $repository,Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $myValue = $session->get('my_key')->getId();
        $u=$repository->find($myValue);

        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $commentRepository = $entityManager->getRepository(Commentaire::class);

            // Fetch all comments associated with the article
            $comments = $commentRepository->findBy(['article' => $article]);

            // Delete each comment
            foreach ($comments as $comment) {
                $entityManager->remove($comment);
            }

            // Flush the changes to the database
            $entityManager->flush();

            // Finally, remove the article
            $entityManager->remove($article);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_article_admin_index', [], Response::HTTP_SEE_OTHER);
    }




    #[Route('/', name: 'app_article_index', methods: ['GET'])]
    public function index(SessionInterface $session, UserRepository $repository,ArticleRepository $articleRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $myValue = $session->get('my_key')->getId();
        $u=$repository->find($myValue);

        // Retrieve the filter parameters from the request
        $authorName = $request->query->get('authorName', '');
        $title = $request->query->get('title', '');
        $sortBy = $request->query->get('sortBy', 'created_at');
    
        // Query articles based on the filter parameters
        $articlesQuery = $articleRepository->createQueryBuilder('a')
            ->leftJoin('a.author', 'author');
    
        if (!empty($authorName)) {
            $articlesQuery->andWhere('author.firstName LIKE :authorName OR author.lastName LIKE :authorName')
                ->setParameter('authorName', '%' . $authorName . '%');
        }
    
        if (!empty($title)) {
            $articlesQuery->andWhere('a.title LIKE :title')
                ->setParameter('title', '%' . $title . '%');
        }
    
        // Paginate the results
        $pagination = $paginator->paginate(
            $articlesQuery->getQuery(), // Doctrine Query, not the result
            $request->query->getInt('page', 1), // Define the page parameter
            6 // Items per page
        );
    
        // Pass filter parameters and paginated articles to the template
        return $this->render('article/index.html.twig', [
            'pagination' => $pagination,
            'authorName' => $authorName,
            'title' => $title,
            'sortBy' => $sortBy,
            'user' =>$u,
        ]);
    }
    


    #[Route('/api', name: 'app_article_api', methods: ['GET'])]
    public function index_api(SessionInterface $session, UserRepository $repository,Request $request,PaginatorInterface $paginator): Response
    {
        $myValue = $session->get('my_key')->getId();
        $u=$repository->find($myValue);

        // Fetch articles from the API endpoint
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://health.gov/myhealthfinder/api/v3/topicsearch.json?lang=en');
        $data = $response->toArray();

        // Extract the articles from the API response
        $articles = $data['Result']['Resources']['Resource'];

         // Paginate the results
         $pagination = $paginator->paginate(
            $articles, // Doctrine Query, not the result
            $request->query->getInt('page', 1), // Define the page parameter
            6 // Items per page
        );

        return $this->render('article/articles.html.twig', [
            'pagination' => $pagination,
            'user'=>$u,
        ]);
    }
    


    #[Route('/api/{id}', name: 'app_article_show_api', methods: ['GET'])]
public function show_api(SessionInterface $session, UserRepository $repository,$id, Request $request): Response
{

    $myValue = $session->get('my_key')->getId();
    $u=$repository->find($myValue);

    // Fetch article details from the API endpoint based on the provided ID
    $client = HttpClient::create();
    $response = $client->request('GET', 'https://health.gov/myhealthfinder/api/v3/topicsearch.json?lang=en');
    $data = $response->toArray();

    // Extract the articles from the API response
    $articles = $data['Result']['Resources']['Resource'];

    // Find the specific article based on the provided ID
    $article = null;
    foreach ($articles as $resource) {
        if ($resource['Id'] == $id) {
            $article = $resource;
            break;
        }
    }

    // If the article is not found, return a JSON response with an error message
    if (!$article) {
        return new JsonResponse(['error' => 'Article not found'], JsonResponse::HTTP_NOT_FOUND);
    }

    // Pass the article details to the template
    return $this->render('article/articleDetailApi.html.twig', [
        'article' => $article,
        'user'=> $u,
    ]);
}

   


    #[Route('/sort', name: 'app_article_sort', methods: ['GET'])]
    public function sort(SessionInterface $session, UserRepository $repository,ArticleRepository $articleRepository, Request $request): JsonResponse
    {
        $myValue = $session->get('my_key')->getId();
        $u=$repository->find($myValue);

        try {
            $sortBy = $request->query->get('sortBy', 'recent');

            // Query articles based on the sorting criteria
            $articlesQuery = $articleRepository->createQueryBuilder('a');

            switch ($sortBy) {
                case 'nb_likes':
                    $articlesQuery->orderBy('a.nbLikes', 'DESC');
                    break;
                case 'nb_comments':
                    $articlesQuery->orderBy('a.nbComments', 'DESC');
                    break;
                case 'title':
                    $articlesQuery->orderBy('a.title', 'ASC');
                    break;
                case 'titleDesc':
                    $articlesQuery->orderBy('a.title', 'DESC');
                    break;
                case 'recent': // Assuming 'created_at' as default sorting criteria
                    $articlesQuery->orderBy('a.created_at', 'DESC');
                    break;
                case 'oldest': // Assuming 'created_at' as default sorting criteria
                    $articlesQuery->orderBy('a.created_at', 'ASC');
                    break;
            }

            // Fetch the sorted articles
            $sortedArticles = $articlesQuery->getQuery()->getResult();

            // Transform articles to array to ensure serialization to JSON
            $formattedArticles = [];
            foreach ($sortedArticles as $article) {
                $formattedArticles[] = [
                    'id' => $article->getId(),
                    'title' => $article->getTitle(),
                    'createdAt' => $article->getCreatedAt()->format('Y-m-d'),
                    'nbLikes' => $article->getNbLikes(),
                    'nbComments' => $article->getNbComments(),
                    'content' => $article->getContenu(),
                    'firstName' => $article->getAuthor()->getFirstName(),
                    'lastName' => $article->getAuthor()->getLastName(),
                    'picture' => $article->getPicture(),
                    // Add other properties as needed
                ];
            }

            // Return sorted articles as JSON response
            return new JsonResponse($formattedArticles);
        } catch (\Exception $e) {
            // Handle exceptions, log errors, and return an appropriate JSON response
            return new JsonResponse(['error' => 'Failed to sort articles.'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    #[Route('/fetch-articles', name: 'fetch_articles', methods: ['GET'])]
    public function fetchArticles(SessionInterface $session, UserRepository $repository,ArticleRepository $articleRepository): JsonResponse
    {
        $myValue = $session->get('my_key')->getId();
        $u=$repository->find($myValue);
        // Fetch articles from the repository
        $articles = $articleRepository->findAll();

        // Transform articles to array format
        $formattedArticles = [];
        foreach ($articles as $article) {
            $formattedArticles[] = [
                'id' => $article->getId(),
                'title' => $article->getTitle(),
                // Add other properties as needed
            ];
        }

        // Return articles as JSON response
        return new JsonResponse($formattedArticles);
    }


    public function uploadFile(UploadedFile $file)
    {
        $guesser = MimeTypes::getDefault();
        $mimeType = $guesser->guessMimeType($file->getPathname());

        return $mimeType;
    }

    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(SessionInterface $session, UserRepository $repository,Request $request, EntityManagerInterface $entityManager): Response
    {
        
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        $myValue = $session->get('my_key')->getId();
        $u=$repository->find($myValue);
        $userID=$u->getId();


        if( $u->getRole() == 'medecin' ) {

    if ($form->isSubmitted() && $form->isValid() ) {
        // Handle picture file upload
        $pictureFile = $form->get('pictureFile')->getData();
        
        if ($pictureFile) {
            // Generate a unique name for the picture file
            $newFilename = uniqid() . '.' . $pictureFile->guessExtension();
            
            // Move the file to the desired directory
            try {
                $pictureFile->move(
                    $this->getParameter('pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload error
                }

                // Update the picture property to store the file name
                $article->setPicture($newFilename);
            }
            
            $article->setAuthor($u);
            $entityManager->persist($article);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
            'user' => $u
        ]);
    }



    return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{id}', name: 'app_article_show', methods: ['GET'])]
    public function show(SessionInterface $session, UserRepository $repository,Request $request, Article $article): Response
    {
        $myValue = $session->get('my_key')->getId();
        $u=$repository->find($myValue);

        // Create an instance of the form for editing comments
        $commentaire = new Commentaire(); // Assuming Commentaire is your comment entity
        $editForm = $this->createForm(CommentaireType::class, $commentaire);

        // Handle form submission for comment editing
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            // Handle form submission logic here, e.g., persisting the comment
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentaire);
            $entityManager->flush();

            // Redirect or perform any other necessary actions after updating the comment
        }

        // Pass the form instance and the article entity to the template
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'editForm' => $editForm->createView(),
            'user'=> $u,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(SessionInterface $session, UserRepository $repository,Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $myValue = $session->get('my_key')->getId();
        $u=$repository->find($myValue);

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $pictureFile */
            $pictureFile = $form->get('pictureFile')->getData();
            if ($pictureFile) {
                $newFilename = uniqid() . '.' . $pictureFile->guessExtension();
                try {
                    $pictureFile->move(
                        $this->getParameter('pictures_directory'),
                        $newFilename
                    );
                    $article->setPicture($newFilename);
                } catch (FileException $e) {
                    // Handle file upload error
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
            'user'=>$u
        ]);
    }

    #[Route('/delete/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(SessionInterface $session, UserRepository $repository,Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $myValue = $session->get('my_key')->getId();
        $u=$repository->find($myValue);

        // if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $commentRepository = $entityManager->getRepository(Commentaire::class);

            // Fetch all comments associated with the article
            $comments = $commentRepository->findBy(['article' => $article]);

            // Delete each comment
            foreach ($comments as $comment) {
                $entityManager->remove($comment);
            }

            // Flush the changes to the database
            $entityManager->flush();

            // Finally, remove the article
            $entityManager->remove($article);
            $entityManager->flush();
        // }

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/download-pdf', name: 'download_pdf')]
    public function downloadPdf(): Response
    {
        // Generate the PDF file
        $pdfFilePath = $this->generatePdf();

        // Create a BinaryFileResponse with the PDF file
        $response = new BinaryFileResponse($pdfFilePath);

        // Set headers for file download
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="Overview_articles.pdf"');

        return $response;
    }

    private function generatePdf(): string
    {

        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Maram TRABLESI');
        $pdf->SetTitle('HealthGuard');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 009', PDF_HEADER_STRING);

        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // Add a new page
        $pdf->AddPage();

        // Define the directory for PDF storage
        $pdfDirectory = 'C:/xampp/htdocs/pidevv/public/pdf/';

        // Ensure that the directory exists, create it if not
        if (!file_exists($pdfDirectory)) {
            mkdir($pdfDirectory, 0777, true); // Create the directory recursively
        }

        $publicDirectory = $this->getParameter('kernel.project_dir') . '/public';

        // Fetch article data from the database
        $entityManager = $this->getDoctrine()->getManager();
        $articleRepository = $entityManager->getRepository(Article::class);
        $articles = $articleRepository->findAll();


        foreach ($articles as $article) {

            // Set background color for the article
        $pdf->SetFillColor(240, 240, 240); // Light gray background


        // Add title
        $pdf->setFont('times', 'B', 20);
        $pdf->SetTextColor(33, 36, 57); // Text color: #212439
        $pdf->Cell(0, 10, $article->getTitle(), 0, 1, 'L', true); // Title with background color
        $pdf->SetTextColor(0, 0, 0); // Reset text color to black for subsequent content


            // Add the picture with 10px padding and taking the whole width
            $picPath = null;
            if ($article->getPicture() !== null) {
                $picPath = $publicDirectory . '/uploads/pictures/' . $article->getPicture();
                if (file_exists($picPath)) {
                    // Add the picture
                    $pdf->Image($picPath, 10, $pdf->GetY() + 10, 186, 0, '', 'C', true, 300);  // Adjust coordinates and size as needed

                }
            }

            $imageHeight = $pdf->getImageRBY();
            $content = wordwrap($article->getContenu(), 100, "\n", true);
            $pdf->SetFont('times', '', 12);
            $pdf->SetTextColor(33, 37, 41); // Text color: #212529
            $pdf->SetFillColor(240, 240, 240); // Content background color: #f9f9ff


            $pdf->SetFont('helvetica', '', 10);
            $pdf->setCellHeightRatio(1.5);
            // $pdf->setCellPaddings(8, 10, 8, 10);
            $pdf->MultiCell(0, 0, '', 0, 'L', false, 1, 125, 160, true, 0, false, true, 0, 'T', false);
            
            // Set the starting position for the content
            if ($picPath == null) {
                $startYContent = $imageHeight + 20; // Add additional padding
            } else {
                $startYContent = $imageHeight + 10; // Add additional padding
            }
            
      
            $pdf->SetY($startYContent); // Set Y position for content
            $pdf->MultiCell(0, 10, $content, 0, 'L', true); // Content with background color
            // Add some additional space after the content
            $pdf->Ln(1);

            // Add author information

            // Set font and text color for the icons and attributes
            $pdf->SetFont('times', '', 12);
            $pdf->SetTextColor(50, 50, 50); // Dark gray text color

            // Define icons using Unicode characters
            // $authorIcon = "\xE2\x9C\x92"; // Unicode character for a check mark icon
            // $likesIcon = "\xE2\x98\x85"; // Unicode character for a star icon
            // $commentsIcon = "\xE2\x9C\x8F"; // Unicode character for a pencil icon
            // Concatenate author's first and last name
            $authorName = $article->getAuthor()->getFirstName() . " " . $article->getAuthor()->getLastName();
            $authorId = $article->getAuthor()->getId();

            // Format creation date
            $createdAt = $article->getCreatedAt()->format('Y-m-d H:i:s');

            // Get number of likes and comments
            $nbLikes = $article->getNbLikes();
            $nbComments = $article->getNbComments();

            // Construct the string with icons and attributes
            $details = "Author: " . $authorName . "  | Id: " . $authorId . "  |  " . $createdAt . "  | " . $nbLikes . " Likes  |  " . $nbComments . " Comments";

            // Output the details
            $pdf->Cell(0, 10, $details, 0, 1, 'L');
            $pdf->AddPage();
        }


        // first patch: f = 0
        $patch_array[0]['f'] = 0;
        $patch_array[0]['points'] = array(
            0.00, 0.00, 0.33, 0.00,
            0.67, 0.00, 1.00, 0.00, 1.00, 0.33,
            0.8, 0.67, 1.00, 1.00, 0.67, 0.8,
            0.33, 1.80, 0.00, 1.00, 0.00, 0.67,
            0.00, 0.33
        );
        $patch_array[0]['colors'][0] = array('r' => 255, 'g' => 255, 'b' => 0);
        $patch_array[0]['colors'][1] = array('r' => 0, 'g' => 0, 'b' => 255);
        $patch_array[0]['colors'][2] = array('r' => 0, 'g' => 255, 'b' => 0);
        $patch_array[0]['colors'][3] = array('r' => 255, 'g' => 0, 'b' => 0);

        // second patch - above the other: f = 2
        $patch_array[1]['f'] = 2;
        $patch_array[1]['points'] = array(
            0.00, 1.33,
            0.00, 1.67, 0.00, 2.00, 0.33, 2.00,
            0.67, 2.00, 1.00, 2.00, 1.00, 1.67,
            1.5, 1.33
        );
        $patch_array[1]['colors'][0] = array('r' => 0, 'g' => 0, 'b' => 0);
        $patch_array[1]['colors'][1] = array('r' => 255, 'g' => 0, 'b' => 255);

        // third patch - right of the above: f = 3
        $patch_array[2]['f'] = 3;
        $patch_array[2]['points'] = array(
            1.33, 0.80,
            1.67, 1.50, 2.00, 1.00, 2.00, 1.33,
            2.00, 1.67, 2.00, 2.00, 1.67, 2.00,
            1.33, 2.00
        );
        $patch_array[2]['colors'][0] = array('r' => 0, 'g' => 255, 'b' => 255);
        $patch_array[2]['colors'][1] = array('r' => 0, 'g' => 0, 'b' => 0);

        // fourth patch - below the above, which means left(?) of the above: f = 1
        $patch_array[3]['f'] = 1;
        $patch_array[3]['points'] = array(
            2.00, 0.67,
            2.00, 0.33, 2.00, 0.00, 1.67, 0.00,
            1.33, 0.00, 1.00, 0.00, 1.00, 0.33,
            0.8, 0.67
        );
        $patch_array[3]['colors'][0] = array('r' => 0, 'g' => 0, 'b' => 0);
        $patch_array[3]['colors'][1] = array('r' => 0, 'g' => 0, 'b' => 255);

        $coords_min = 0;
        $coords_max = 2;

        $pdf->CoonsPatchMesh(10, 45, 190, 200, '', '', '', '', $patch_array, $coords_min, $coords_max);




        // Define the path where the PDF file will be saved
        $pdfFilePath = $pdfDirectory . 'Overview_articles.pdf';

        // Save the PDF file
        $pdf->Output($pdfFilePath, 'F');

        return $pdfFilePath;
    }


    #[Route('/download_Details/{id}', name: 'download_Details', methods: ['GET'])]
    public function downloadDetails($id, ArticleRepository $articleRepository): Response
    {

        $article = $articleRepository->find($id);

        // Generate the PDF file
        $pdfFilePath = $this->generatePdfDetails($article);

        // Create a BinaryFileResponse with the PDF file
        $response = new BinaryFileResponse($pdfFilePath);

        // Set headers for file download
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="Article_Details.pdf"');

        return $response;
    }


    private function generatePdfDetails($article): string
    {
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Maram TRABLESI');
        $pdf->SetTitle('HealthGuard');

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 009', PDF_HEADER_STRING);

        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // Add a new page
        $pdf->AddPage();

        // Define the directory for PDF storage
        $pdfDirectory = 'C:/xampp/htdocs/pidevv/public/pdf/';

        // Ensure that the directory exists, create it if not
        if (!file_exists($pdfDirectory)) {
            mkdir($pdfDirectory, 0777, true); // Create the directory recursively
        }

        $publicDirectory = $this->getParameter('kernel.project_dir') . '/public';

        // Set background color for the article
        $pdf->SetFillColor(240, 240, 240); // Light gray background


        // Add title
        $pdf->setFont('times', 'B', 20);
        $pdf->SetTextColor(33, 36, 57); // Text color: #212439
        $pdf->Cell(0, 10, $article->getTitle(), 0, 1, 'L', true); // Title with background color
        $pdf->SetTextColor(0, 0, 0); // Reset text color to black for subsequent content

        // Add the picture with 10px padding and taking the whole width
        $picPath = null;
        if ($article->getPicture() !== null) {
            $picPath = $publicDirectory . '/uploads/pictures/' . $article->getPicture();
            if (file_exists($picPath)) {
                // Add the picture
                $pdf->Image($picPath, 10, $pdf->GetY() + 10, 186, 0, '', 'C', true, 300);  // Adjust coordinates and size as needed

            }
        }

        $imageHeight = $pdf->getImageRBY();
        $content = wordwrap($article->getContenu(), 100, "\n", true);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetTextColor(33, 37, 41); // Text color: #212529
        $pdf->SetFillColor(240, 240, 240); // Content background color: #f9f9ff

        // Add left border to content cell
        $pdf->SetDrawColor(255, 94, 19); // Border color: #ff5e13
        $pdf->SetLineWidth(0.5); // Border thickness: 2px

        $pdf->setCellHeightRatio(1.5);
        $pdf->MultiCell(0, 0, '', 0, 'L', false, 1, 125, 160, true, 0, false, true, 0, 'T', false);
        $pdf->setCellPaddings(8, 10, 8, 10);

        // Set the starting position for the content
        $startYContent = $imageHeight + 10; // Add additional padding

        $pdf->SetY($startYContent); // Set Y position for content
        $pdf->MultiCell(0, 10, $content, 'L', 'L', true); // Content with background color and left border
        // Add some additional space after the content
        $pdf->Ln(1);

        // Add author information
        // Set font and text color for the icons and attributes
        $pdf->SetFont('times', '', 12);
        $pdf->SetTextColor(50, 50, 50); // Dark gray text color

        // // Define icons using Unicode characters
        // $authorIcon = "\xE2\x9C\x92"; // Unicode character for a check mark icon
        // $likesIcon = "\xE2\x98\x85"; // Unicode character for a star icon
        // $commentsIcon = "\xE2\x9C\x8F"; // Unicode character for a pencil icon
        // Concatenate author's first and last name
        $authorName = $article->getAuthor()->getFirstName() . " " . $article->getAuthor()->getLastName();

        // Format creation date
        $createdAt = $article->getCreatedAt()->format('Y-m-d H:i:s');

        // Get number of likes and comments
        $nbLikes = $article->getNbLikes();
        $nbComments = $article->getNbComments();

        // Construct the string with icons and attributes
        $details = "Author:  " . $authorName . "  |  " . $createdAt . "  | " . $nbLikes . " Likes  |  " . $nbComments . " Comments";

        // Output the details
        // $pdf->Cell(0, 5, $details, 0, 1, 'L');

        $pdf->AddPage();


        // first patch: f = 0
        $patch_array[0]['f'] = 0;
        $patch_array[0]['points'] = array(
            0.00, 0.00, 0.33, 0.00,
            0.67, 0.00, 1.00, 0.00, 1.00, 0.33,
            0.8, 0.67, 1.00, 1.00, 0.67, 0.8,
            0.33, 1.80, 0.00, 1.00, 0.00, 0.67,
            0.00, 0.33
        );
        $patch_array[0]['colors'][0] = array('r' => 255, 'g' => 255, 'b' => 0);
        $patch_array[0]['colors'][1] = array('r' => 0, 'g' => 0, 'b' => 255);
        $patch_array[0]['colors'][2] = array('r' => 0, 'g' => 255, 'b' => 0);
        $patch_array[0]['colors'][3] = array('r' => 255, 'g' => 0, 'b' => 0);

        // second patch - above the other: f = 2
        $patch_array[1]['f'] = 2;
        $patch_array[1]['points'] = array(
            0.00, 1.33,
            0.00, 1.67, 0.00, 2.00, 0.33, 2.00,
            0.67, 2.00, 1.00, 2.00, 1.00, 1.67,
            1.5, 1.33
        );
        $patch_array[1]['colors'][0] = array('r' => 0, 'g' => 0, 'b' => 0);
        $patch_array[1]['colors'][1] = array('r' => 255, 'g' => 0, 'b' => 255);

        // third patch - right of the above: f = 3
        $patch_array[2]['f'] = 3;
        $patch_array[2]['points'] = array(
            1.33, 0.80,
            1.67, 1.50, 2.00, 1.00, 2.00, 1.33,
            2.00, 1.67, 2.00, 2.00, 1.67, 2.00,
            1.33, 2.00
        );
        $patch_array[2]['colors'][0] = array('r' => 0, 'g' => 255, 'b' => 255);
        $patch_array[2]['colors'][1] = array('r' => 0, 'g' => 0, 'b' => 0);

        // fourth patch - below the above, which means left(?) of the above: f = 1
        $patch_array[3]['f'] = 1;
        $patch_array[3]['points'] = array(
            2.00, 0.67,
            2.00, 0.33, 2.00, 0.00, 1.67, 0.00,
            1.33, 0.00, 1.00, 0.00, 1.00, 0.33,
            0.8, 0.67
        );
        $patch_array[3]['colors'][0] = array('r' => 0, 'g' => 0, 'b' => 0);
        $patch_array[3]['colors'][1] = array('r' => 0, 'g' => 0, 'b' => 255);

        $coords_min = 0;
        $coords_max = 2;

        $pdf->CoonsPatchMesh(10, 45, 190, 200, '', '', '', '', $patch_array, $coords_min, $coords_max);

        // Define the path where the PDF file will be saved
        $pdfFilePath = $pdfDirectory . 'Article_Details.pdf';

        // Save the PDF file
        $pdf->Output($pdfFilePath, 'F');

        return $pdfFilePath;
    }
}
