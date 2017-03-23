<?php

namespace CYA\YogaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormError;
use CYA\YogaBundle\Entity\Tipoproducto;
use CYA\YogaBundle\Form\TipoproductoType;

class TipoproductoController extends Controller
{
    public function indexAction(Request $request)
    {
        $searchQuery = $request->get('query');

        if(!empty($searchQuery)){
            $finder = $this->container->get('fos_elastica.finder.app.tipoproducto');
            $tipoproductos = $finder->createPaginatorAdapter($searchQuery);
        }else{
            $em = $this->getDoctrine()->getManager();
            $dql = "SELECT r FROM CYAYogaBundle:Tipoproducto r ORDER BY r.id DESC";
            $tipoproductos = $em->createQuery($dql);  
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $tipoproductos, $request->query->getInt('page' , 1),
            10
        );
        
        return $this->render('CYAYogaBundle:Tipoproducto:index.html.twig', array('pagination' => $pagination, 'tipoproductos' => $tipoproductos));
    }
    
    public function addAction(Request $request)
    {
        $tipoproducto = new Tipoproducto();
        
        $form = $this->createForm(TipoproductoType::class, $tipoproducto);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
                $em = $this->getDoctrine()->getManager();
                $em->persist($tipoproducto);
                $em->flush();
                
                $this->addFlash('mensaje', 'El tipo producto ha sido creado');
                
                return $this->redirectToRoute('cya_tipoproducto_index');
        }
      
        return $this->render('CYAYogaBundle:Tipoproducto:add.html.twig', array('form' => $form->createView()));
    }

   public function editAction($id, Request $request)
   {
        $em = $this->getDoctrine()->getManager();
        $tipoproducto = $em->getRepository('CYAYogaBundle:Tipoproducto')->find($id);
        $form = $this->createForm(TipoproductoType::class, $tipoproducto);
        $form->handleRequest($request); 
        
        if(!$tipoproducto){
            throw $this->createNotFoundException('Tipo producto no encontrado');
        }
       
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em->flush();
            
            $this->addFlash('mensaje', 'El tipo producto ha sido modificado');
            
            return $this->redirectToRoute('cya_tipoproducto_index');
        }
       
        return $this->render('CYAYogaBundle:Tipoproducto:edit.html.twig', array('tipoproducto' => $tipoproducto, 'form' => $form->createView()));
   }
}
