<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\User;
use App\Controller\Response;


class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
   
    /**
     * @route("/ajoute",name="ajoute")
     */
    public function ajouter( Request $request){
        $em = $this->getDoctrine()->getManager();
        $user = new User();
        $fb = $this->createFormBuilder($user)
            ->add('email',EmailType::class)
            ->add('username',TextType::class)
            ->add('password',TextType::class )
            ->add("valider",SubmitType::class);
        $form = $fb->getForm();
        $form->handleRequest($request);
        if( $form->isSubmitted() ){
            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute("affiche");
        }
        return $this->render("User/ajoute.html.twig",
        ["f" => $form->createView()] ); 
    }

    /**
     * @route("/affiche",name="affiche")
     */
    public function affiche( Request $request){
        $em=$this->getDoctrine()->getManager();
        $repo=$em->getRepository( User::class) ;
        $listeUser   = $repo->findAll();
        return $this->render("User/liste.html.twig",[
            "liste"=>$listeUser
        ]);
    }

    /**
     * @route("/supprimer/{id}",name="supprimer")
     */
    public function supprimer( Request $request ,$id ) {
        $em=$this->getDoctrine()->getManager();
        $repo=$em->getRepository( User::class) ;
        $User   = $repo->find($id);
        if(! $User){
            throw $this->createNotFoundException("No User".$id);
        }
        $em->remove($User);
        $em->flush();

        return $this->redirectToRoute('affiche');
    }

    /**
     * @route("/details/{id}" , name="detail")
     */
    public function plusdedetails (Request $request ,$id){
        $em=$this->getDoctrine()->getManager();
        $repo=$em->getRepository( User::class) ;
        $User   = $repo->find($id);
        if(! $User){
            throw $this->createNotFoundException("No User".$id);
        }
        return $this->render("User/plusDeDetails.html.twig",[
            "User"=>$User
        ]);
    }

    /**
     * @route("/modification/{id}" , name="modification")
     */
    public function modification (Request $request ,$id){
        $em = $this->getDoctrine()->getRepository(User::class)->find($id);
        
        $fb = $this->createFormBuilder($em)
            ->add('email',EmailType::class)
            ->add('username',TextType::class)
            ->add('password',TextType::class )
            ->add("valider",SubmitType::class);
        $form = $fb->getForm();
        $form->handleRequest($request);
        if( $form->isSubmitted() ){
            $em=$this->getDoctrine()->getManager(); 
            $em->flush();
            return $this->redirectToRoute("affiche");
        }
        return $this->render("User/modification.html.twig",
        ["f" => $form->createView()] ); 
    } 
    /**
     * @route("/recherche" , name="recherche")
     */
    public function recherche (Request $request ){
        $params = $request->query->all();
        $email=$request->get('email');
        $em=$this->getDoctrine()->getManager();
        $repo=$em->getRepository( User::class) ;
        $User   = $repo->findBy(array('email'=>$email));
        if(! $User){
            throw $this->createNotFoundException("No User".$email);
           // return $this->redirectToRoute("affiche");
        }
        return $this->render("User/recherche.html.twig",[
            "User"=>$User
        ]);
    }
} 