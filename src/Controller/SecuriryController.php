<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\UserType;
use App\Entity\User;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;

class SecuriryController extends AbstractController
{
    /**
     * @Route("/securiry", name="securiry")
     */
    public function index()
    {
        return $this->render('securiry/index.html.twig', [
            'controller_name' => 'SecuriryController',
        ]);
    }

    /**
     * @Route("/register", name="user_registration")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('show',['id'=>$user->getId(),
            ]);
        }

        return $this->render('securiry/form.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/show/{id}", name="show")
     */
    public function show2(User $user)
    {
        return $this->render('securiry/show.html.twig',[
            'user'=>$user,
        ]);
    }

    /**
     * @Route("/login", name="login")
     */

    public function login(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('securiry/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logoutAction()
    {
        //return $this->redirectToRoute('accueil');

        throw new \Exception('this should not be reached!');
    }



}
