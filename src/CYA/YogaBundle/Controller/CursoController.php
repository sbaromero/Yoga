<?php

namespace CYA\YogaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormError;
use CYA\YogaBundle\Entity\Curso;
use CYA\YogaBundle\Form\CursoType;

class CursoController extends Controller
{
    public function indexAction(Request $request)
    {
        $searchQuery = $request->get('query');

  //    if(!empty($searchQuery)){
   //          $finder = $this->container->get('fos_elastica.finder.app.locker');
   //         $lockers = $finder->createPaginatorAdapter($searchQuery);
   //     }else{
           
            $em = $this->getDoctrine()->getManager();
            $dql = "SELECT r FROM CYAYogaBundle:Curso r ORDER BY r.id DESC";
            $cursos = $em->createQuery($dql);  
 //       }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
        $cursos, $request->query->getInt('page' , 1), 10);
        
        return $this->render('CYAYogaBundle:Curso:index.html.twig', array('pagination' => $pagination, 'cursos' => $cursos));
    }
    
    public function addAction(Request $request)
    {
        $curso = new Curso();
        
        $form = $this->createForm(CursoType::class, $curso);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
                $em = $this->getDoctrine()->getManager();
                $em->persist($curso);
                $em->flush();
                
                $this->addFlash('mensaje', 'El Curso ha sido creado');
                
                return $this->redirectToRoute('cya_curso_index');
        }
      
        return $this->render('CYAYogaBundle:Curso:add.html.twig', array('form' => $form->createView()));
    }

   public function editAction($id, Request $request)
   {
        $em = $this->getDoctrine()->getManager();
        $curso = $em->getRepository('CYAYogaBundle:Curso')->find($id);
        $form = $this->createForm(CursoType::class, $curso);
        $form->handleRequest($request); 
        
        if(!$curso){
            throw $this->createNotFoundException('Curso no encontrado');
        }
       
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em->flush();
            
            $this->addFlash('mensaje', 'El curso ha sido modificado');
            
            return $this->redirectToRoute('cya_curso_index');
        }
       
        return $this->render('CYAYogaBundle:Curso:edit.html.twig', array('curso' => $curso, 'form' => $form->createView()));
   }

 public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $curso = $em->getRepository('CYAYogaBundle:Curso')->find($id);
        $em->remove($curso);
        $em->flush();  
        $successMessage = 'Curso eliminado';
        $this->addFlash('mensaje', $successMessage);

        return $this->redirectToRoute('cya_curso_index');
    }
    
    
    
}
