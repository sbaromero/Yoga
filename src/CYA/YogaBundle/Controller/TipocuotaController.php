<?php

namespace CYA\YogaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use CYA\YogaBundle\Entity\Tipocuota;
use CYA\YogaBundle\Form\TipocuotaType;

class TipocuotaController extends Controller
{
    public function indexAction(Request $request)
    {
        $searchQuery = $request->get('query');

        if(!empty($searchQuery)){
            $finder = $this->container->get('fos_elastica.finder.app.tipocuota');
            $tipocuotas = $finder->createPaginatorAdapter($searchQuery);
        }else{
            $em = $this->getDoctrine()->getManager();
            $dql = "SELECT t FROM CYAYogaBundle:Tipocuota t ORDER BY t.id DESC";
            $tipocuotas = $em->createQuery($dql);  
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $tipocuotas, $request->query->getInt('page' , 1),
            10
        );
        
        return $this->render('CYAYogaBundle:Tipocuota:index.html.twig', array('pagination' => $pagination, 'tipocuotas' => $tipocuotas));
    }
    
    public function addAction(Request $request)
    {
        $tipocuota = new Tipocuota();
        
        $form = $this->createForm(TipocuotaType::class, $tipocuota);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
                $em = $this->getDoctrine()->getManager();
                $em->persist($tipocuota);
                $em->flush();
                
                $this->addFlash('mensaje', 'El tipo de cuota ha sido creado');
                
                return $this->redirectToRoute('cya_tipocuota_index');
        }
        
        return $this->render('CYAYogaBundle:Tipocuota:add.html.twig', array('form' => $form->createView()));
    }
    
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $tipocuota = $em->getRepository('CYAYogaBundle:Tipocuota')->find($id);
        $form = $this->createForm(TipocuotaType::class, $tipocuota);
        $form->handleRequest($request); 
        
        if(!$tipocuota){
            throw $this->createNotFoundException('Tipo de cuota no encontrado');
        }
       
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em->flush();
            
            $this->addFlash('mensaje', 'El tipo de cuota ha sido modificado');
            
            return $this->redirectToRoute('cya_tipocuota_index');
        }
       
        return $this->render('CYAYogaBundle:Tipocuota:edit.html.twig', array('tipocuota' => $tipocuota, 'form' => $form->createView()));
    }
}
