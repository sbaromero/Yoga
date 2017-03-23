<?php

namespace CYA\YogaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormError;
use CYA\YogaBundle\Entity\Rubro;
use CYA\YogaBundle\Form\RubroType;

class RubroController extends Controller
{
    public function indexAction(Request $request)
    {
        $searchQuery = $request->get('query');

        if(!empty($searchQuery)){
            $finder = $this->container->get('fos_elastica.finder.app.rubro');
            $rubros = $finder->createPaginatorAdapter($searchQuery);
        }else{
            $em = $this->getDoctrine()->getManager();
            $dql = "SELECT r FROM CYAYogaBundle:Rubro r ORDER BY r.id DESC";
            $rubros = $em->createQuery($dql);  
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $rubros, $request->query->getInt('page' , 1),
            10
        );
        
        return $this->render('CYAYogaBundle:Rubro:index.html.twig', array('pagination' => $pagination, 'rubros' => $rubros));
    }
    
    public function addAction(Request $request)
    {
        $rubro = new Rubro();
        
        $form = $this->createForm(RubroType::class, $rubro);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
                $em = $this->getDoctrine()->getManager();
                $em->persist($rubro);
                $em->flush();
                
                $this->addFlash('mensaje', 'El rubro ha sido creado');
                
                return $this->redirectToRoute('cya_rubro_index');
        }
        
        return $this->render('CYAYogaBundle:Rubro:add.html.twig', array('form' => $form->createView()));
    }

   public function editAction($id, Request $request)
   {
        $em = $this->getDoctrine()->getManager();
        $rubro = $em->getRepository('CYAYogaBundle:Rubro')->find($id);
        $form = $this->createForm(RubroType::class, $rubro);
        $form->handleRequest($request); 
        
        if(!$rubro){
            throw $this->createNotFoundException('Rubro no encontrado');
        }
       
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em->flush();
            
            $this->addFlash('mensaje', 'El rubro ha sido modificado');
            
            return $this->redirectToRoute('cya_rubro_index');
        }
       
        return $this->render('CYAYogaBundle:Rubro:edit.html.twig', array('rubro' => $rubro, 'form' => $form->createView()));
   }
}
