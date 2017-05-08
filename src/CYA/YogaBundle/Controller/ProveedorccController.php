<?php

namespace CYA\YogaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormError;
use CYA\YogaBundle\Entity\Proveedor;
use CYA\YogaBundle\Entity\Proveedorcc;
use CYA\YogaBundle\Form\ProveedorccType;
use CYA\YogaBundle\Form\ProveedorpagoType;
use CYA\YogaBundle\Entity\Movimiento;
use CYA\YogaBundle\Entity\Rubro;

class ProveedorccController extends Controller
{
    public function indexAction(Request $request)
    {
       
        $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Proveedor');
        $query = $repository->createQueryBuilder('u')
            ->where('1=1')
            ->getQuery();
        $proveedores = $query->getResult();
        
        
        $proQuery = $request->get('proveedorq');
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT p FROM CYAYogaBundle:Proveedorcc p";
        
         if(!empty($proQuery)){
                $dql = $dql . " where p.proveedor in (SELECT t FROM CYAYogaBundle:Proveedor t WHERE t.id = " . $proQuery .")";
            }
        
        
        $dql=$dql." ORDER BY p.id DESC";
        
        $proveedorcc = $em->createQuery($dql);  
       
        
       

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $proveedorcc, $request->query->getInt('page' , 1),
            20
        );
        
        return $this->render('CYAYogaBundle:Proveedorcc:index.html.twig', 
        array('pagination' => $pagination, 'proveedores' => $proveedores,'proveedorcc' => $proveedorcc));
    }
    
    public function addAction(Request $request)
    {
        
        $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Proveedor');
        $query = $repository->createQueryBuilder('u')
            ->where('1=1')
            ->getQuery();
        $proveedores = $query->getResult();

        
        
        $proveedorcc= new Proveedorcc();
        $form = $this->createForm(ProveedorccType::class, $proveedorcc);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
               
               $proveedorid = $request->get('proveedor'); 
               if ($proveedorid != null){
               
                $proveedorelegido = $repository->findOneById($proveedorid);
                $proveedorcc->setProveedor($proveedorelegido);
               
                $em = $this->getDoctrine()->getManager();
                $em->persist($proveedorcc);
                $em->flush();
                
                $this->addFlash('mensaje', 'CC. con Proveedor ha sido creada');
                return $this->redirectToRoute('cya_proveedorcc_index');
               }
               
        }
      
        return $this->render('CYAYogaBundle:Proveedorcc:add.html.twig', array('proveedores'=>$proveedores,'form' => $form->createView()));
    }

   public function editAction($id, Request $request)
   {
          
        $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Proveedor');
        $query = $repository->createQueryBuilder('u')
            ->where('1=1')
            ->getQuery();
        $proveedores = $query->getResult();

        
        $em = $this->getDoctrine()->getManager();
        $proveedorcc = $em->getRepository('CYAYogaBundle:Proveedorcc')->find($id);
        $idelegido = $proveedorcc->getProveedor()->getId();
        $form = $this->createForm(ProveedorccType::class, $proveedorcc);
        $form->handleRequest($request); 
        
        if(!$proveedorcc){
            throw $this->createNotFoundException('CC de Proveedor  no encontrada');
        }
       
        if ($form->isSubmitted() && $form->isValid()) {
            
              $proveedorid = $request->get('proveedor'); 
             if ($proveedorid != null){
               
                $proveedorelegido = $repository->findOneById($proveedorid);
                $proveedorcc->setProveedor($proveedorelegido);
            
            $em->flush();
            
            $this->addFlash('mensaje', 'La cc de proveedor ha sido modificada');
            
            return $this->redirectToRoute('cya_proveedorcc_index');
             }
            
        }
       
        return $this->render('CYAYogaBundle:Proveedorcc:edit.html.twig', array('idelegido'=>$idelegido,'proveedores'=>$proveedores,
        'proveedorcc' => $proveedorcc, 'form' => $form->createView()));
   }

 public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $proveedorcc = $em->getRepository('CYAYogaBundle:Proveedorcc')->find($id);
        $em->remove($proveedorcc);
        $em->flush();  
        $successMessage = 'Cuenta Corriente de Proveedor eliminada, realice el movimiento correctivo correspondiente en caja.';
        $this->addFlash('mensaje', $successMessage);
       

        return $this->redirectToRoute('cya_proveedorcc_index');
    }
    
 public function pagoAction($id, Request $request)
   {
          
        
         $em = $this->getDoctrine()->getManager();
         $proveedorcc = $em->getRepository('CYAYogaBundle:Proveedorcc')->find($id);
         $idelegido = $proveedorcc->getProveedor()->getId();
         $form = $this->createForm(ProveedorpagoType::class, $proveedorcc);
         $form->handleRequest($request); 
        
        if(!$proveedorcc){
            throw $this->createNotFoundException('CC de Proveedor  no encontrada');
        }
       
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em->flush();
            
            
             //CAJA
            $movimiento = new Movimiento();
            $movimiento->setTipo('PP');
            $movimiento->SetDescripcion('Pago a Proveedor: '.$proveedorcc->getProveedor()->getNombre());
            $movimiento->setUsuario($this->get('security.token_storage')->getToken()->getUser());
            $movimiento->setFecha(new \DateTime("now"));
            $monto = $proveedorcc->getPagado();
            $movimiento->setMonto($monto);
            
            $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Rubro');
            $rubro = $repository->findOneByNombre('PAGO A PROVEEDORES');
            if(!$rubro){
                $rubro = new Rubro();
                $rubro->setNombre('PAGO A PROVEEDORES');
                $rubro->setTipo('D');
                $rubro->setIsActive(1);
                $em->persist($rubro);
                $em->flush(); 
                $movimiento->setRubro($rubro);
            }else{
                $movimiento->setRubro($rubro);}
                
                
            
            $em->persist($movimiento);
            $em->flush(); 
       
            
            
            $this->addFlash('mensaje', 'La cc de proveedor ha sido modificada');
            return $this->redirectToRoute('cya_proveedorcc_index');
            
        }
       
      
       
       
        return $this->render('CYAYogaBundle:Proveedorcc:pago.html.twig', 
        array(
            // 'idelegido'=>$idelegido,
            // 'proveedores'=>$proveedores,
            // 'proveedorcc' => $proveedorcc, 
            'form' => $form->createView()));
   
}
    
}
