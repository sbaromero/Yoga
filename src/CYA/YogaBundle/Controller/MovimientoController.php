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
use CYA\YogaBundle\Form\MovimientoType;
use Doctrine\Common\Collections\ArrayCollection;

class MovimientoController extends Controller
{
    public function addAction(Request $request)
    {
        $movimiento = new Movimiento();
        $form = $this->createForm(MovimientoType::class, $movimiento);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $movimiento->setUsuario($this->get('security.token_storage')->getToken()->getUser());
            $movimiento->setTipo('MB');
            $em = $this->getDoctrine()->getManager();
            $em->persist($movimiento);
            $em->flush();
            
            $successMessage = 'Movimiento asentado';
            $this->addFlash('mensaje', $successMessage);
            
            return $this->redirectToRoute('cya_movimiento_add');
        }
      
        $saldo = 0;  

        $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Movimiento');
        $query = $repository->createQueryBuilder('m')
        ->orderBy('m.fecha', 'DESC')
        ->getQuery();
        $movimientos = $query->getResult();

        foreach($movimientos as $mov){
            if($mov->getRubro()->getTipo() == 'D'){
                $saldo = $saldo - $mov->getMonto();
            }else{
                $saldo = $saldo + $mov->getMonto();
            }
        }
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $movimientos, $request->query->getInt('page' , 1),
            10
        );

        return $this->render('CYAYogaBundle:Movimiento:add.html.twig', array('pagination' => $pagination, 'movimientos' => $movimientos, 'saldo' => $saldo,'form' => $form->createView()));
    }
    
    public function indexAction(Request $request)
    {
        $searchQuery = $request->get('query');
        $tipoQuery = $request->get('tipo');
        $dcQuery = $request->get('dc');
        $fhQuery = $request->get('fh');
        $fdQuery = $request->get('fd');
        $rubroQuery = $request->get('rubro');
        $usuarioQuery = $request->get('usuario'); 
        
        $select = "SELECT m FROM CYAYogaBundle:Movimiento m WHERE m.monto > 0";

            
        if(!empty($tipoQuery)){
            $select = $select . " AND m.tipo = '" . $tipoQuery ."'";
        }
        
        if(!empty($dcQuery)){
            $select = $select . " AND m.rubro in (SELECT r FROM CYAYogaBundle:Rubro r WHERE r.tipo = '" . $dcQuery ."')";
        }
        
        if(!empty($rubroQuery)){
            $select = $select . " AND m.rubro = (SELECT ru FROM CYAYogaBundle:Rubro ru WHERE ru.id = " . $rubroQuery .")";
        }
        
        if(!empty($fhQuery)){
            $select = $select . " AND m.fecha <= '" . $fhQuery ." 23:59:59'";
        }
        
        if(!empty($fdQuery)){
            $select = $select . " AND m.fecha >= '" . $fdQuery ." 00:00:00'";
        }
        
        if(!empty($usuarioQuery)){
            $select = $select . " AND m.usuario = (SELECT u FROM CYAYogaBundle:Usuario u WHERE u.id = " . $usuarioQuery .")";
        }
        
        $select = $select . " ORDER BY m.fecha DESC";
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery($select);
        $movimientos = $query->getResult();

     
      
         $saldo2 = 0;  

        foreach($movimientos as $mov){
            if($mov->getRubro()->getTipo() == 'D'){
                $saldo2 = $saldo2 - $mov->getMonto();
            }else{
                $saldo2 = $saldo2 + $mov->getMonto();
            }
        }
     
     
     
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $movimientos, $request->query->getInt('page' , 1),
            30
        );
        $em = $this->getDoctrine()->getManager();
        $rubros = $em->getRepository('CYAYogaBundle:Rubro')->findAll();
        
        
        $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Usuario');
        $query = $repository->createQueryBuilder('u')
            ->where('u.rol != :rol')
            ->setParameter('rol', 'ROLE_USER')
            ->getQuery();
        $usuarios = $query->getResult();
        
        
         $saldo = 0;  

        $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Movimiento');
        $query = $repository->createQueryBuilder('m')
        ->orderBy('m.fecha', 'DESC')
        ->getQuery();
        $movimientos = $query->getResult();

        foreach($movimientos as $mov){
            if($mov->getRubro()->getTipo() == 'D'){
                $saldo = $saldo - $mov->getMonto();
            }else{
                $saldo = $saldo + $mov->getMonto();
            }
        }
        
        
        
        
        return $this->render('CYAYogaBundle:Movimiento:index.html.twig', 
        array('saldo2' => $saldo2,'saldo' => $saldo,'pagination' => $pagination, 'rubros' => $rubros, 'usuarios' => $usuarios));
    }
    
    public function deleteAction($id, Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        $movimiento = $em->getRepository('CYAYogaBundle:Movimiento')->find($id);
       
        if(!$movimiento){
            throw $this->createNotFoundException('Movimiento no encontrado');
        }   
        
        if($movimiento->getTipo() == 'MB'){
            $em->remove($movimiento);
            $em->flush(); 
        }
        
        if($movimiento->getTipo() == 'CC'){
            $alumnocc = new Alumnocc();
            $alumnocc = $movimiento->getAlumnocc();
            if($alumnocc->getTipo() == 'PC'){
                $montoborrado = $alumnocc->getPagado() - $movimiento->getMonto();
                $alumnocc->setPagado($montoborrado);
                $alumnocc->setFechamodificacion(new \DateTime("now"));
            }

            $em->remove($movimiento);
            $em->flush(); 
            if($alumnocc->getTipo() == 'PD'){
                $em->remove($alumnocc);
                $em->flush(); 
            }
            
        }
        
        if($movimiento->getTipo() == 'VP'){
            
            $maestroventa = new Maestroventa();
            $maestroventa = $movimiento->getMaestroventa();
            
            $detalleventas = $maestroventa->getDetalleventas();
            
            foreach($detalleventas as $detalle){
            
                $producto = new Producto();
                $producto = $detalle->getProducto();
                $producto->setStock($producto->getStock() + $detalle->getCantidad());
                $em->flush();

                $em->remove($detalle);
                $em->flush();
            }
            
            $em->remove($maestroventa);
            $em->remove($movimiento);
            $em->flush();
        }

        
        $successMessage = 'Movimiento eliminado';
        $this->addFlash('mensaje', $successMessage);

        return $this->redirectToRoute('cya_movimiento_index');
    }
    
    public function detailsAction($id, Request $request)
    {
        if(!empty($id)){
            $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Movimiento');
            $movimiento = $repository->findOneById($id);
            $maestroventa = $movimiento->getMaestroventa();
            $detalleventas = $maestroventa->getDetalleventas();

            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $detalleventas, $request->query->getInt('page' , 1),
               15
            );
            
            $fechaventa = (string)$maestroventa->getFecha()->format('d/m/Y');
            return $this->render('CYAYogaBundle:Movimiento:details.html.twig',array('pagination' => $pagination, 'fecha' => $fechaventa, 'total' => $maestroventa->getTotal()));
        }else{
            $this->addFlash('mensaje', 'El detalle no está disponible');
            return $this->redirectToRoute('cya_movimiento_index');
        }
    }
    
}
