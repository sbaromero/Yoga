<?php

namespace CYA\YogaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormError;
use CYA\YogaBundle\Entity\Locker;
use CYA\YogaBundle\Form\LockerType;

class LockerController extends Controller
{
    public function indexAction(Request $request)
    {
        $searchQuery = $request->get('query');

        if(!empty($searchQuery)){
            $finder = $this->container->get('fos_elastica.finder.app.locker');
            $lockers = $finder->createPaginatorAdapter($searchQuery);
        }else{
            $em = $this->getDoctrine()->getManager();
            $dql = "SELECT r FROM CYAYogaBundle:Locker r ORDER BY r.id DESC";
            $lockers = $em->createQuery($dql);  
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $lockers, $request->query->getInt('page' , 1),
            10
        );
        
        return $this->render('CYAYogaBundle:Locker:index.html.twig', array('pagination' => $pagination, 'lockers' => $lockers));
    }
    
    public function addAction(Request $request)
    {
        $locker = new Locker();
        
        $form = $this->createForm(LockerType::class, $locker);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
                $em = $this->getDoctrine()->getManager();
                $locker->setisactive(0);
                $em->persist($locker);
                $em->flush();
                
                $this->addFlash('mensaje', 'El Locker ha sido creado');
                
                return $this->redirectToRoute('cya_locker_index');
        }
      
        return $this->render('CYAYogaBundle:Locker:add.html.twig', array('form' => $form->createView()));
    }

   public function editAction($id, Request $request)
   {
        $em = $this->getDoctrine()->getManager();
        $locker = $em->getRepository('CYAYogaBundle:Locker')->find($id);
        $form = $this->createForm(LockerType::class, $locker);
        $form->handleRequest($request); 
        
        if(!$locker){
            throw $this->createNotFoundException('Locker no encontrado');
        }
       
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em->flush();
            
            $this->addFlash('mensaje', 'El locker ha sido modificado');
            
            return $this->redirectToRoute('cya_locker_index');
        }
       
        return $this->render('CYAYogaBundle:Locker:edit.html.twig', array('locker' => $locker, 'form' => $form->createView()));
   }

 public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $locker = $em->getRepository('CYAYogaBundle:Locker')->find($id);
        $em->remove($locker);
        $em->flush();  
        $successMessage = 'Locker eliminado';
        $this->addFlash('mensaje', $successMessage);

        return $this->redirectToRoute('cya_locker_index');
    }
    
    
    
}
