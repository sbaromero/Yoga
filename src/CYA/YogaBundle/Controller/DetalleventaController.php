<?php

namespace CYA\YogaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use CYA\YogaBundle\Entity\Rubro;
use CYA\YogaBundle\Entity\Alumnocc;
use CYA\YogaBundle\Entity\Usuario;
use CYA\YogaBundle\Entity\Producto;
use CYA\YogaBundle\Entity\Movimiento;
use CYA\YogaBundle\Entity\Maestroventa;
use CYA\YogaBundle\Entity\Detalleventa;
use CYA\YogaBundle\Form\DetalleventaType;
use CYA\YogaBundle\Form\ProductoType;
use CYA\YogaBundle\Form\MovimientoType;


class DetalleventaController extends Controller
{
    public function addAction(Request $request)
    {
        $detalleventa = new Detalleventa();
        $form = $this->createForm(DetalleventaType::class, $detalleventa);
        $form->handleRequest($request);
        
        /*combo de productos*/
        $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Producto');
        $query = $repository->createQueryBuilder('u')
            ->orderBy('u.descripcion', 'ASC')
            ->where('u.isActive = :rol')
            ->setParameter('rol', '1')

            ->getQuery();
        $productos = $query->getResult();
        $productoQuery = $request->get('producto'); 
        
        
        if ($form->isSubmitted() && $form->isValid()) 
        {
             /*
            $movimiento->setUsuario($this->get('security.token_storage')->getToken()->getUser());*/
            
             /*recupero valores necesarios del producto elegido*/
            if (!$productoQuery)
        {
              $this->addFlash('mensaje', 'Elija un producto');
             return $this->redirectToRoute('cya_detalleventa_add');
            
        }
            
            $id = $productoQuery;
            $em = $this->getDoctrine()->getManager();
            $arrayproducto  = $em->getRepository('CYAYogaBundle:Producto')->find($id);
            
            $nombreproducto = $arrayproducto->getDescripcion(); 
            $precioproducto = $arrayproducto->getPreciolista();
            $cantidad       = $detalleventa->getCantidad();
        
            $preciototal = $precioproducto *  $cantidad;
            $detalleventa->setPrecioproducto($preciototal); 
            $detalleventa->setNombreproducto($nombreproducto);
            $detalleventa->setProducto($arrayproducto);
            
            $stock = $arrayproducto->getStock($id)-$cantidad;
           
           if ($cantidad < 0)
            {
                
                     $this->addFlash('mensaje', 'La cantidad no puede ser menor a cero');
                      return $this->redirectToRoute('cya_detalleventa_add');
            }
            
           
            if ($stock < 0)
            {
                
                     $this->addFlash('mensaje', "No alcanza el stock del producto, stock actual:".$arrayproducto->getStock($id));
                      return $this->redirectToRoute('cya_detalleventa_add');
            }
            
            
            
            $arrayproducto->setStock($arrayproducto->getStock($id)-$cantidad);
           
            $em = $this->getDoctrine()->getManager();
            $em->persist($detalleventa);
            $em->flush(); 
            $successMessage = 'Producto agregado: '.$nombreproducto. " - Precio Unitario:  ".$precioproducto. " - Cantidad: ".$cantidad.", - Precio Total: ".$preciototal. " - Stock restante: ".$stock ;
            $this->addFlash('mensaje', $successMessage);
            
            
            
            return $this->redirectToRoute('cya_detalleventa_add');
        }
      
        $saldo = 0;  
        $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Detalleventa');
        $query = $repository->createQueryBuilder('m')
        ->where('m.maestroventa is NULL')
        ->getQuery();
        $detalleventa = $query->getResult();

        foreach($detalleventa as $det)
        {
            $saldo = $saldo + $det->getPrecioproducto();
       
        }
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($detalleventa, $request->query->getInt('page', 1), 5);
        
        /*return $this->render('CYAYogaBundle:Detalleventa:add.html.twig', array('pagination' => $pagination, 'detalleventa' => $detalleventa,'form' => $form->createView()));*/
        return $this->render('CYAYogaBundle:Detalleventa:add.html.twig', array('form' => $form->createView(), 'saldo' => $saldo, 'productos' => $productos,'pagination' => $pagination, 'detalleventa' => $detalleventa ));
      
    }

    public function deleteAction($id, Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        $detalleventa = $em->getRepository('CYAYogaBundle:Detalleventa')->find($id);
       
        if(!$detalleventa)
        {
            throw $this->createNotFoundException('Producto no encontrado');
        }   
        
        //corrijo stock, primero obtengo el objeto producto en una variable, despues busco el id para sumarle stock
        $idprod = $detalleventa->getProducto();
        $idp = $idprod->getId();
        $em = $this->getDoctrine()->getManager();
        $arrayproducto  = $em->getRepository('CYAYogaBundle:Producto')->find($idp);
        $arrayproducto->setStock($arrayproducto->getStock()+$detalleventa->getCantidad());
        
        /*elimino*/
        $em->remove($detalleventa);
        $em->flush(); 
     

        
        $successMessage = 'Producto eliminado de esta venta';
        $this->addFlash('mensaje', $successMessage);

        return $this->redirectToRoute('cya_detalleventa_add');
    }
    
}
