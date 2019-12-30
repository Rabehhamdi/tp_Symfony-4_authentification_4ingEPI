<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\CKEditorBundle\Form\Type\CKEditorType; 

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/ckeditor", name="ckeditor")
     */
    public function ckeditor()
    {
        $form = $this->createFormBuilder()
            ->add('content',CKEditorType::class,[
                'config'=>[
                    'uiColor'=>"#e2e2e2",
                    'toolbar'=>'full',
                    'required'=>true
                ]
            ])
            ->getForm();
        return $this->render('commantaire.html.twig',array('form' => $form->createView()));
    }
}
