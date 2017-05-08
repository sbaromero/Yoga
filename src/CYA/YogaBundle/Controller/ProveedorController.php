<?php

namespace CYA\YogaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormError;
use CYA\YogaBundle\Entity\Proveedor;
use CYA\YogaBundle\Form\ProveedorType;


class ProveedorController extends Controller
{
    public function indexAction(Request $request)
    {
      
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT p FROM CYAYogaBundle:Proveedor p ORDER BY p.id DESC";
        $proveedores = $em->createQuery($dql);  
       

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $proveedores, $request->query->getInt('page' , 1),
            1000
        );
        
        return $this->render('CYAYogaBundle:Proveedor:index.html.twig', 
        array('pagination' => $pagination, 'proveedores' => $proveedores));
    }
    
    public function addAction(Request $request)
    {
        $proveedor= new Proveedor();
        
        $form = $this->createForm(ProveedorType::class, $proveedor);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
                $em = $this->getDoctrine()->getManager();
                $em->persist($proveedor);
                $em->flush();
                
                $this->addFlash('mensaje', 'El Proveedor ha sido creado');
                
                return $this->redirectToRoute('cya_proveedor_index');
        }
      
        return $this->render('CYAYogaBundle:Proveedor:add.html.twig', array('form' => $form->createView()));
    }

   public function editAction($id, Request $request)
   {
        $em = $this->getDoctrine()->getManager();
        $proveedor = $em->getRepository('CYAYogaBundle:Proveedor')->find($id);
        $form = $this->createForm(ProveedorType::class, $proveedor);
        $form->handleRequest($request); 
        
        if(!$proveedor){
            throw $this->createNotFoundException('Proveedor no encontrado');
        }
       
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em->flush();
            
            $this->addFlash('mensaje', 'El proveedor ha sido modificado');
            
            return $this->redirectToRoute('cya_proveedor_index');
        }
       
        return $this->render('CYAYogaBundle:Proveedor:edit.html.twig', array('proveedor' => $proveedor, 'form' => $form->createView()));
   }

 public function deleteAction($id, Request $request)
    {
        
        try {
        $em = $this->getDoctrine()->getManager();
        $proveedor = $em->getRepository('CYAYogaBundle:Proveedor')->find($id);
        $em->remove($proveedor);
        $em->flush();  
        $successMessage = 'Proveedor eliminado';
        $this->addFlash('mensaje', $successMessage);

        
         } catch (\Exception $e) {
           $this->addFlash('error', 'Imposible eliminar este Proveedor, verifique que no tenga cuentas asociadas.');
            }

        return $this->redirectToRoute('cya_proveedor_index');
    }
    
    
   
}
