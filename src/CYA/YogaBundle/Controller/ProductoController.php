<?php

namespace CYA\YogaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormError;
use CYA\YogaBundle\Entity\Producto;
use CYA\YogaBundle\Form\ProductoType;

class ProductoController extends Controller
{   
    public function deleteAction($id, Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        $producto = $em->getRepository('CYAYogaBundle:Producto')->find($id);
       
        if(!$producto)
        {
            throw $this->createNotFoundException('producto no encontrado');
        }   
        
        

        try
        {
        
        $nombre = $producto->getDescripcion();
        $em->remove($producto);
        $em->flush();

        
        $successMessage = 'Producto '. $nombre.' eliminado';
        $this->addFlash('mensaje', $successMessage);

        return $this->redirectToRoute('cya_producto_index');
        
        }
        
        
       catch(\Exception $e)
       {
        $successMessage = 'Producto '. $nombre.' no se puede eliminar, es probable que esté incluido en una venta, pruebe con cambiarlo a inactivo';
        $this->addFlash('mensaje', $successMessage); 
         return $this->redirectToRoute('cya_producto_index');
       }
        
        
    }
    
    
    
    
    public function indexAction(Request $request)
    {
        $searchQuery = $request->get('query');
        $tcQuery = $request->get('tipoproducto');
            
        if(!empty($searchQuery)){
            $finder = $this->container->get('fos_elastica.finder.app.producto');
            $productos = $finder->createPaginatorAdapter($searchQuery);
        }else{
            $em = $this->getDoctrine()->getManager();
            /*$dql = "SELECT r FROM CYAYogaBundle:Producto r ORDER BY r.id DESC";*/
            
             $dql = "SELECT u FROM CYAYogaBundle:Producto u WHERE u.id > 0";
        
       /*    if ($estadoQuery == '1'){
                $dql = $dql . "AND u.isActive = 1";
                
            }
            
            if ($estadoQuery == '0'){
                $dql = $dql . "AND u.isActive = 0";
            }*/
            
            if(!empty($tcQuery)){
                $dql = $dql . " AND u.tipoproducto = (SELECT t FROM CYAYogaBundle:Tipoproducto t WHERE t.id = " . $tcQuery .")";
            }
            
            $dql = $dql . " ORDER BY u.id DESC";
            
            
            
            
            $productos = $em->createQuery($dql);  
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $productos, $request->query->getInt('page' , 1),
            30
        );
        
        $em = $this->getDoctrine()->getManager();
        $tipoproductos = $em->getRepository('CYAYogaBundle:Tipoproducto')->findAll();
        
        return $this->render('CYAYogaBundle:Producto:index.html.twig', array('pagination' => $pagination, 'productos' => $productos,'tipoproductos' => $tipoproductos));
        
        
      
        
        
    }
    
    public function addAction(Request $request)
    {
        $producto = new Producto();
        
        $form = $this->createForm(ProductoType::class, $producto);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
                $em = $this->getDoctrine()->getManager();
                $em->persist($producto);
                $em->flush();
                
                $this->addFlash('mensaje', 'El producto '.$producto->getDescripcion().' ha sido creado');
                
                return $this->redirectToRoute('cya_producto_index');
        }
      
        return $this->render('CYAYogaBundle:Producto:add.html.twig', array('form' => $form->createView()));
    }

   public function editAction($id, Request $request)
   {
        $em = $this->getDoctrine()->getManager();
        $producto = $em->getRepository('CYAYogaBundle:Producto')->find($id);
        $form = $this->createForm(ProductoType::class, $producto);
        $form->handleRequest($request); 
        
        if(!$producto){
            throw $this->createNotFoundException('Producto no encontrado');
        }
       
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em->flush();
            
            $this->addFlash('mensaje', 'El producto '.$producto->getDescripcion().' ha sido modificado');
            
            return $this->redirectToRoute('cya_producto_index');
        }
       
        return $this->render('CYAYogaBundle:Producto:edit.html.twig', array('producto' => $producto, 'form' => $form->createView()));
   }
}
