<?php

namespace App\Controller;

use Twilio\Rest\Client;
use App\Entity\User;
use App\Form\UserType;
use App\Form\LoginType;
use App\Form\MdpType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\UserRepository;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use League\OAuth2\Client\Provider\Google;
use Symfony\Component\HttpFoundation\Session\SessionInterface;




class UserController extends AbstractController
{
    #[Route('/users', name: 'app_user', methods: ['GET'])]
    public function index(SessionInterface $session, UserRepository $x): Response
    {
        $myValue = $session->get('my_key')->getId();
        $u = $x->find($myValue);

        $formations = $x->findAll();
        $User = new User();

        return $this->render(
            'user/admin_show.html.twig',
            array("users" => $formations, "user" => $u)
        );
    }

    #[Route('/addUser', name: 'app_AddUser')]
    public function addUser(SessionInterface $session, Request $request, ManagerRegistry  $doctrine, UserRepository $x): Response
    {
        $User = new User();

        $form = $this->createForm(UserType::class, $User);
        $form->handleRequest($request);

        $myValue = $session->get('my_key')->getId();
        $u = $x->find($myValue);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('Img')->getData();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move('C:\Users\MSI\Desktop\PI-Dev\public\upload', $fileName);

            $User->setImg("upload/" . $fileName);
            $User->setEtat(1);

            $em = $doctrine->getManager();
            $em->persist($User);
            $em->flush();

            $transport = Transport::fromDsn('smtp://assistancerh926@gmail.com:bmsquhdlkbvnoanm@smtp.gmail.com:587?verify_peer=0');
            $mailer = new Mailer($transport); { {

                    $email = (new Email());
                    $email->from('assistancerh926@gmail.com');
                    $email->to(new Address($form->get('Email')->getData()));
                    $email->subject('Creation de votre compte');

                    $email->html('<h3>Bonjour ' . $form->get('Nom')->getData() . '</h3><p>
                    Vos cordoonnes sont : <br>
                    <p>Email : ' . $form->get('Email')->getData() . '</p>
                    <p>Mot de passe : ' . $form->get('Password')->getData() . '</p> <br>
                    </p><h4>Adminstrateur Healthguard </h4>');


                    $mailer->send($email);

                    return $this->redirectToRoute('app_user');
                }
            }

            return $this->render('user/admin_new.html.twig', array("form" => $form->createView(), "user" => $u));
        }
    }

    #[Route('/modifier/{id}', name: 'app_UpdateUser')]
    public function updateUser(SessionInterface $session, Request $request, int $id, ManagerRegistry  $doctrine, UserRepository $x): Response
    {
        $User = new User();
        $User = $x->find($id);
        $form = $this->createForm(UserType::class, $User);

        $form->handleRequest($request);
        $myValue = $session->get('my_key')->getId();
        $u = $x->find($myValue);

        if ($form->isSubmitted() && $form->isValid()) {
            $User1 = $form->getdata();
            if ($User1->getRate() == 'medecin') {
                $User->setFirstName($User1->getFirstName());
                $User->setLastName($User1->getLastName());
                $User->setEmail($User1->getEmail());
                $User->setRole($User1->getRole());
                $User->setRegion($User1->getRegion());
                $User->setPhoneNumber($User1->getPhoneNumber());
                $User->setSpecialite($User1->getSpecialite());
                $User->setRate($User1->getRate());

                $file = $form->get('Img')->getData();
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move('C:\Users\MSI\Desktop\PI-Dev\public\upload', $fileName);

                $User->setImg("upload/" . $fileName);
            } else if ($User1->getRate() == 'patient') {
                $User->setFirstName($User1->getFirstName());
                $User->setLastName($User1->getLastName());
                $User->setEmail($User1->getEmail());
                $User->setRegion($User1->getRegion());
                $User->setPhoneNumber($User1->getPhoneNumber());
            }

            $em = $doctrine->getManager();
            $em->flush();

            return $this->redirectToRoute('app_user');
        }
        return $this->render('user/admin_edit.html.twig', array("form" => $form->createView(), "user" => $u));
    }

    #[Route('/supprimer/{id}', name: 'app_RemoveUser')]
    public function removeUser(ManagerRegistry $doctrine, UserRepository $repository, $id)
    {

        $User1 = $repository->find($id);
        $em = $doctrine->getManager();
        $em->remove($User1);
        $em->flush();
        return  $this->redirectToRoute("app_user");
    }


    #[Route('/login', name: 'app_login')]

    public function Login(ManagerRegistry  $doctrine, Request $request, UserRepository  $x, SessionInterface $session): Response
    {
        $User = new User();
        $check_u = new User();
        $form = $this->createForm(LoginType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $User = $form->getdata();

            $check_u = $x->findByExampleField($User["email"]);
            if ($form->isSubmitted()) {
                $User = $form->getData();
                $check_u = $x->findByExampleField($User["email"]);

                if (isset($check_u[0])) {
                    if ($User["email"] == $check_u[0]->getEmail() && $User["mdp"] == $check_u[0]->getPassword() && $check_u[0]->getEtat() == 1) {
                        $session->set('my_key', $check_u[0]);
                        return $this->redirectToRoute("app_user_logged");
                    } else {
                        $this->addFlash('error', 'Email ou mot de passe sont incorrect.'); // Add this line for the flash message
                        return $this->render('user/login1.html.twig', ["form" => $form->createView()]);
                    }
                }
            }
        }
        return $this->render('user/login1.html.twig', ["form" => $form->createView()]);
    }




    #[Route('/inscriptionPatient', name: 'app_Inscription_patient')]
    public function InscriptionPatient(SessionInterface $session, Request $request, ManagerRegistry  $doctrine, UserRepository $x): Response
    {
        $User = new User();
        $form = $this->createForm(UserType::class, $User);
        $form->remove("role");
        $form->remove("specialite");
        $form->remove("rate");
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $User->setRole("patient");
            $User->setEtat(0);
            $session->set('my_key', $User);

            $em = $doctrine->getManager();
            $em->persist($User);
            $em->flush();
            $transport = Transport::fromDsn('smtp://assistancerh926@gmail.com:bmsquhdlkbvnoanm@smtp.gmail.com:587?verify_peer=0');
            $mailer = new Mailer($transport);
            $us = $x->findOneByEmail($User->getEmail()); { {
                    $email = (new Email());
                    $email->from('assistancerh926@gmail.com');
                    $email->to(new Address($us->getEmail()));
                    $email->subject('Activation de votre compte');

                    $email->html('<h3>Bonjour monsieur/madame' . $us->getFirstName() . '</h3><p>
                     a fin de activer le compte de ce mail:
                     <code>' . $us->getEmail() . '</code></p><p>
                     <a href="http://127.0.0.1:8000/Activation">Clicker ici pour Activer votre compte </a></p><h4>Adminstrateur Healthguard</h4>');

                    $mailer->send($email);
                    $this->addFlash('success', "Un email d'activation a été envoyé à votre adresse email. Veuillez vérifier votre boîte de réception pour activer votre compte");
                    return $this->redirectToRoute('app_login');
                }
            }
        }
        return $this->render('user/inscrciption_patient.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/inscriptionMedecin', name: 'app_Inscription_medecin')]
    public function InscriptionMedecin(SessionInterface $session, Request $request, ManagerRegistry  $doctrine, UserRepository $x): Response
    {
        $User = new User();
        $form = $this->createForm(UserType::class, $User);
        $form->remove("role");
        $form->remove("rate");
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('Img')->getData();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move('C:\Users\MSI\Desktop\PI-Dev\public\upload', $fileName);

            $User->setRole("medecin");
            $User->setImg("upload/" . $fileName);
            $User->setEtat(0);
            $session->set('my_key', $User);

            $em = $doctrine->getManager();
            $em->persist($User);
            $em->flush();
            $transport = Transport::fromDsn('smtp://assistancerh926@gmail.com:bmsquhdlkbvnoanm@smtp.gmail.com:587?verify_peer=0');
            $mailer = new Mailer($transport);
            $us = $x->findOneByEmail($User->getEmail()); { {
                    $email = (new Email());
                    $email->from('assistancerh926@gmail.com');
                    $email->to(new Address($us->getEmail()));
                    $email->subject('Activation de votre compte');

                    $email->html('<h3>Bonjour monsieur/madame' . $us->getFirstNom() . '</h3><p>
                     a fin de activer le compte de ce mail:
                     <code>' . $us->getEmail() . '</code></p><p>
                     <a href="http://127.0.0.1:8000/Activation">Clicker ici pour Activer votre compte </a></p><h4>Adminstrateur Healthguard</h4>');

                    $mailer->send($email);
                    $this->addFlash('success', "Un email d'activation a été envoyé à votre adresse email. Veuillez vérifier votre boîte de réception pour activer votre compte");
                    return $this->redirectToRoute('app_login');
                }
            }
        }
        return $this->render('user/inscription_medecin.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/Activation', name: 'app_Activation')]
    public function Activation(SessionInterface $session, UserRepository  $x, ManagerRegistry  $doctrine)
    {

        $us = $x->findOneByEmail($session->get('my_key')->getEmail());

        $us->setEtat(1);
        $em = $doctrine->getManager();
        $em->flush();
        return $this->redirectToRoute("app_user_logged");
    }




    #[Route('/acceuil_logged', name: 'app_user_logged')]
    public function front_log(SessionInterface $session, UserRepository $x, ManagerRegistry  $doctrine, Request $request)
    {
        $myValue = $session->get('my_key')->getId();
        $u = $x->find($myValue);
        if ($u->getRole() == "medecin")
            return $this->render('user/acceuil_medecin.html.twig', array("user" => $u));
        else if ($u->getRole() == "patient")
            return $this->render('user/acceuil_patient.html.twig', array("user" => $u));
        else if ($u->getRole() == "Admin")
            return $this->redirectToRoute("app_user");
    }


    #[Route('/profil', name: 'app_user_profil')]
    public function profile_log(SessionInterface $session, UserRepository $x, ManagerRegistry  $doctrine, Request $request)
    {
        $myValue = $session->get('my_key')->getId();
        $u = $x->find($myValue);
        $User = new User();
        $User = $x->find($u->getId());
        $form = $this->createForm(UserType::class, $User);
        $form->remove("role");
        $form->remove("rate");
        $form->handleRequest($request);

        $u = $x->find($myValue);


        // $form->get('nom')->setdata($User->getNom());

        if ($u->getRole() == "medecin") {
            if ($form->isSubmitted() && $form->isValid()) {
                $User1 = $form->getdata();
                $User->setFirstName($User1->getFirstName());
                $User->setLastName($User1->getLastName());
                $User->setEmail($User1->getEmail());
                $User->setRegion($User1->getRegion());
                $User->setPhoneNumber($User1->getPhoneNumber());
                $User->setSpecialite($User1->getSpecialite());

                $file = $form->get('Img')->getData();
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move('C:\Users\MSI\Desktop\PI-Dev\public\upload', $fileName);

                $User->setImg("upload/" . $fileName);

                $em = $doctrine->getManager();
                $em->flush();
                return $this->redirectToRoute("app_user_profil");
            }
            return $this->render('user/profile_medecin.html.twig', array("user" => $u, "form" => $form->createView()));
        } else if ($u->getRole() == "patient") {
            $form->remove("specialite");

            if ($form->isSubmitted() && $form->isValid()) {
                $User1 = $form->getdata();
                $User->setFirstName($User1->getFirstName());
                $User->setLastName($User1->getLastName());
                $User->setEmail($User1->getEmail());
                $User->setRegion($User1->getRegion());
                $User->setPhoneNumber($User1->getPhoneNumber());

                $em = $doctrine->getManager();
                $em->flush();
                return $this->redirectToRoute("app_user_profil");
            }

            return $this->render('user/profile_patient.html.twig', array("user" => $u, "form" => $form->createView()));
        } else if ($u->getRole() == "Admin")
            return $this->render('user/profile_admin.html.twig', array("user" => $u, "form" => $form->createView()));
    }

    private $provider;
    private function getProvider(): Google
    {
        return $this->provider;
    }


    public function __construct()
    {
        $this->provider = new Google([
            'clientId'          => $_ENV['Google_ID'],
            'clientSecret'      => $_ENV['Google_SECRET'],
            'redirectUri'       => $_ENV['Google_CALLBACK'],
            'graphApiVersion'   => 'v15.0',
        ]);
    }

    #[Route('/fcb-login', name: 'fcb_login')]
    public function ggleLogin(): Response
    {

        $helper_url = $this->getprovider()->getAuthorizationUrl();

        return $this->redirect($helper_url);
    }


    #[Route('/fcb-callback', name: 'fcb_callback')]
    public function ggleCallBack(UserRepository $userDb, SessionInterface $session, ManagerRegistry  $doctrine)
    {
        //Récupérer le token
        $token = $this->provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        //Récupérer les informations de l'user

        $user = $this->provider->getResourceOwner($token);
        $user = $user->toArray();

        //Vérifier si l'user existe dans la base des données

        $User = $userDb->findOneByEmail($user['email']);

        if ($User) {

            $session->set('my_key', $User);
            return $this->redirectToRoute("app_user_logged");
        } else {
            $new_user = new User();
            $new_user->setFirstName($user['given_name']);
            $new_user->setLastName($user['family_name']);
            $new_user->setEmail($user['email']);
            $new_user->setImg($user['picture']);
            $new_user->setRegion("Megrine");
            $new_user->setRole("patient");
            $new_user->setPhoneNumber(00000000);
            $new_user->setPassword(sha1(str_shuffle('abscdop123390hHHH;:::OOOI')));
            $new_user->setEtat(1);

            $em = $doctrine->getManager();
            $em->persist($new_user);
            $em->flush();
            $session->set('my_key', $new_user);

            return $this->redirectToRoute("app_user_logged");
        }
    }
    #[Route('/mdp_oublie', name: 'app_confirm_rdv')]
    public function ConfRdvv(Request $request, ManagerRegistry $doctrine, UserRepository $x, SessionInterface $session)
    {
        $us = new User();
        $form = $this->createForm(MdpType::class);
        $form->handleRequest($request);
        //$us=$x->findOneByEmail($session->get('my_key')->getEmail()); 


        if ($form->isSubmitted() && $form->isValid()) {
            $us = $x->findById($form->getdata());


            $twilio_number = "+16189823797";
            $accountSid = 'AC69e7af0f7166b47f02469c582f9b0d6f';
            $authToken = '15e3f2105c5e37dd2c82e807e7740ebd';
            $twilio = new Client($accountSid, $authToken);
            $message = $twilio->messages->create("+216" . $us->getPhoneNumber(), array('from' => '+16189823797', 'body' => 'Bonjour monsiour ' . $us->getFirstName() . 'Votre mot de pass est :' . $us->getPassword(),));
            if ($message->sid) {
                $sms = 'SMS sent successfully.';
                $this->addFlash('success', " la reclamation a ete envoyée avec succeée");
                //$form->getData();

                return  $this->redirectToRoute("app_login");
            } else {
                $sms = 'Failed to send SMS.';
            }
        }

        return $this->render('user/mdp_oublie.html.twig', array("form" => $form->createView()));
    }
}
