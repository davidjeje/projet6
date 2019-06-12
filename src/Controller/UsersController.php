<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UsersType;
use App\Form\UserType;
use App\Form\UserShowType;
use App\Form\ProfilType;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Entity\Tricks;
use App\Form\TricksType;
use App\Repository\TricksRepository;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @Route("/users")
 */
class UsersController extends AbstractController
{
    /**
     * @Route("/", name="users_index", methods="GET")
     */
    public function index(UsersRepository $usersRepository): Response
    {
        return $this->render('users/index.html.twig', ['users' => $usersRepository->findAll()]);
    }

    /**
     * @Route("/new/user", name="users_new", methods="GET|POST")
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);
        $email = $user->getEmail();
        $token = $user->getToken();

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            
            $user->setIsActive(false);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $message = (new \Swift_Message('Nous vous souhaitons la bienvenu. Clic sur le lien pour valider ton inscription ! A bientôt !!!'))
                ->setFrom('dada.pepe.alal@gmail.com')
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                        'users/texte.html.twig',
                        ['token' => $token]
                    ),
                    'text/html'
                );

            $mailer->send($message);
            
            $this->addFlash('success', 'Votre compte à bien été enregistré. Rendez vous sur votre boite mail pour finaliser votre inscription. Merci.');

            return $this->redirectToRoute('tricks_index');
        }

        return $this->render(
            'users/new.html.twig',
            [
            'user' => $user,
            'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/validate/{token}", name="validate", methods="GET|POST")
     */
    public function validateAccount($token, UsersRepository $usersRepository)
    {
        $user = $usersRepository->findOneBy(array("token"=>$token));

        $user->setIsActive(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        $session = new Session();
        $session->start();
        return $this->redirectToRoute('login');
    }
    /**
     * @Route("/change/{token}", name="changePassword", methods="GET|POST")
     */
    public function changePassword($token, UsersRepository $usersRepository, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $usersRepository->findOneBy(array("token"=>$token));
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            
            $user->setIsActive(true);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('login');
        }

        return $this->render(
            'users/changePass.html.twig',
            [
            'user' => $user,
            'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/login/user", name="login", methods="GET|POST")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();
        
        $form = $this->get('form.factory')
            ->createNamedBuilder(null)
            ->add('_username', null, ['label' => 'Email'])
            ->add('_password', \Symfony\Component\Form\Extension\Core\Type\PasswordType::class, ['label' => 'Mot de passe'])
                
            ->getForm();

        if ($form->isSubmitted() && $form->isValid()) {
            $session = new Session();
            $session->start();
            return $this->redirectToRoute('tricks_index');
        }
        return $this->render(
            'users/connexion.html.twig',
            [
                    'mainNavLogin' => true,
                    'title' => 'Connexion',
                    'form' => $form->createView(),
                    'last_username' => $lastUsername,
                    'error' => $error,
            ]
        );
    }


    /**
     * @Route("/forgot/password", name="forgot", methods="GET|POST")
     */
    public function forgot(Request $request, \Swift_Mailer $mailer, UsersRepository $usersRepository)
    {
        $form = $this->get('form.factory')
            ->createNamedBuilder(null)
            ->add('_username', null, ['label' => 'Email'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $request->request->get('_username');
            $user = $usersRepository->findOneBy(array("email"=>$email));
            $user->setToken(bin2hex(random_bytes(16)));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $message = (new \Swift_Message('Nous vous souhaitons la bienvenu. Cliquer sur le lien pour pouvoir Réinitialiser votre mot de passe ! A bientôt !!!'))
                ->setFrom('dada.pepe.alal@gmail.com')
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                        'users/reset.html.twig',
                        ['token' => $user->getToken()]
                    ),
                    'text/html'
                );

            $mailer->send($message);
            
            $this->addFlash('o', 'Vous venez de valider votre adresse mail. Rendez vous dans votre boite mail pour accepter la demande de changement de mot de passe');

            return $this->redirectToRoute('tricks_index');
        }
        

        
        
        return $this->render(
            'users/forgot.html.twig',
            [
                    'mainNavLogin' => true, 'title' => 'Mot de passe oublier',
                   
                    'form' => $form->createView(),
                    
                    'error' => null,
            ]
        );
    }

    /**
     * @Route("/{id}/show", name="users_show", methods="GET")
     */
    public function show(User $user): Response
    {
        return $this->render('users/show.html.twig', ['user' => $user]);
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }


    /**
     * @Route("/{id}/edit/user", name="users_edit", methods="GET|POST")
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $file = $user->getPhoto();
            
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            
            try {
                $file->move($this->getParameter('images_directory'), $fileName);
                $user->setPhoto($fileName);
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Vos modifications ont bien été prise en compte.');
            } catch (FileException $e) {
                $this->addFlash('error', "Vos modifications n'ont pas été prise en compte.");
            }
            

            return $this->redirectToRoute('tricks_index', ['id' => $user->getId()]);
        }

        return $this->render(
            'users/edit.html.twig',
            [
            'mainNavLogin' => true,
            'user' => $user,
            'form' => $form->createView(),
            'error' => null,
            ]
        );
    }
    

    /**
     * @Route("/logout/user", name="logout", methods="GET|POST")
     */
    public function logout(Request $request, User $user): Response
    {
        $session->destroy();
        return $this->render(
            'tricks/index.html.twig',
            [
                    'mainNavLogin' => false,
                    'title' => 'Deconnexion',
                    'error' => null,
            ]
        );
    }

    /**
     * @Route("/{id}/delete/user", name="users_delete", methods="DELETE")
     */
    public function delete(Request $request, User $user): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        

        return $this->redirectToRoute('tricks_index');
    }
}
