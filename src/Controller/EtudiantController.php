<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
 
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Etudiant;
use App\Controller\Response;

class EtudiantController extends AbstractController
{
    /**
     * @Route("/etudiant", name="etudiant")
     */
    public function index()
    {
        return $this->render('etudiant/index.html.twig', [
            'controller_name' => 'EtudiantController',
        ]);
    }


    /**
     * @route("/AjouteEtudiant",name="ajouteEtudiant")
     */
    public function ajouter( Request $request){
        $em = $this->getDoctrine()->getManager();
        $Etudiant = new Etudiant();
        $fb = $this->createFormBuilder($Etudiant)
            ->add('nom',TextType::class)
            ->add('email',EmailType::class)
            ->add('class',TextType::class)
            ->add('password',TextType::class )
            ->add("valider",SubmitType::class);
        $form = $fb->getForm();
        $form->handleRequest($request);
        if( $form->isSubmitted() ){
            $em=$this->getDoctrine()->getManager();
            $em->persist($Etudiant);
            $em->flush();
            return $this->redirectToRoute("afficheEtudiant");
        }
        return $this->render("etudiant/index.html.twig",
            ["f" => $form->createView()] );
    }

    /**
     * @route("/afficheEtudiant",name="afficheEtudiant")
     */
    public function affiche( Request $request){
        $em=$this->getDoctrine()->getManager();
        $repo=$em->getRepository( Etudiant::class) ;
        $listeEtudiant   = $repo->findAll();
        return $this->render("etudiant/liste.html.twig",[
            "liste"=>$listeEtudiant
        ]);
    }

    /**
     * @route("/supprimerEtudiant/{id}",name="supprimerEtudiant")
     */
    public function supprimer( Request $request ,$id ) {
        $em=$this->getDoctrine()->getManager();
        $repo=$em->getRepository( Etudiant::class) ;
        $Etudiant   = $repo->find($id);
        if(! $Etudiant){
            throw $this->createNotFoundException("No Etudiant".$id);
        }
        $em->remove($Etudiant);
        $em->flush();

        return $this->redirectToRoute('afficheEtudiant');
    }

    /**
     * @route("/detailsEtudiant/{id}" , name="detailEtudiant")
     */
    public function plusdedetailsEtudiant (Request $request ,$id){
        $em=$this->getDoctrine()->getManager();
        $repo=$em->getRepository( Etudiant::class) ;
        $Etudiant   = $repo->find($id);
        if(! $Etudiant){
            throw $this->createNotFoundException("No Etudiant".$id);
        }
        return $this->render("etudiant/plusDeDetails.html.twig",[
            "etudiant"=>$Etudiant
        ]);
    }

    /**
     * @route("/rechercheEtudiant" , name="rechercheEtudiant")
     */
    public function recherche (Request $request )
    {
        $params = $request->query->all();
        $email = $request->get('email');
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Etudiant::class);
        $Etudiant = $repo->findBy(array('email' => $email));
        if (!$Etudiant) {
            throw $this->createNotFoundException("No User" . $email);
            // return $this->redirectToRoute("affiche");
        }
        return $this->render("etudiant/recherche.html.twig", [
            "etudiant" => $Etudiant
        ]);
    }


    /**
     * @route("/modificationEtudiant/{id}" , name="modificationEtudiant")
     */
    public function modification (Request $request ,$id){
        $em = $this->getDoctrine()->getRepository(Etudiant::class)->find($id);

        $fb = $this->createFormBuilder($em)
            ->add('nom',TextType::class)
            ->add('email',EmailType::class)
            ->add('class',TextType::class)
            ->add('password',TextType::class )
            ->add("valider",SubmitType::class);
        $form = $fb->getForm();
        $form->handleRequest($request);
        if( $form->isSubmitted() ){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("afficheEtudiant");
        }
        return $this->render("etudiant/modification.html.twig",
            ["f" => $form->createView()] );
    }

}
