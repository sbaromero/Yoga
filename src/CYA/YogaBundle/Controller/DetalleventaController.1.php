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
            
            $id = $productoQuery;
            $em = $this->getDoctrine()->getManager();
            $arrayproducto  = $em->getRepository('CYAYogaBundle:Producto')->find($id);
            
            $nombreproducto = $arrayproducto->getDescripcion(); 
            $precioproducto = $arrayproducto->getPreciolista();
            $cantidad       = $detalleventa->getCantidad();
        
            $detalleventa->setNombreproducto($nombreproducto);
            $detalleventa->setPrecioproducto($precioproducto *  $cantidad);             
           
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($detalleventa);
            $em->flush(); 
            $successMessage = 'Producto agregado: '.$nombreproducto. ', Precio Unitario: $'.$precioproducto. ', Cantidad: '.$cantidad ;
            $this->addFlash('mensaje', $successMessage);
           
        );
            
            return $this->redirectToRoute('cya_detalleventa_add');
        }//end subbmited
     
       /* $saldo = 0;  
        $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Detalleventa');
        $query = $repository->createQueryBuilder('m')
        ->orderBy('m.id', 'DESC')
        ->getQuery();
        $detalleventa = $query->getResult();

        foreach($detalleventa as $det)
        {
            $saldo = $saldo + $det->getPrecioproducto();
       
        }
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($detalleventa, $request->query->getInt('page', 1), 10);
        
            */
       /* return $this->render('CYAYogaBundle:Detalleventa:add.html.twig', array('pagination' => $pagination,'form' => $form->createView())); */
        return $this->render('CYAYogaBundle:Detalleventa:add.html.twig', array('productos' => $productos,'detalleventa' => $detalleventa,'form' => $form->createView()));
      
    }
  /*  
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
            $select = $select . " AND m.rubro = (SELECT r FROM CYAYogaBundle:Rubro r WHERE r.id = " . $rubroQuery .")";
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


        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $movimientos, $request->query->getInt('page' , 1),
            10
        );
        $em = $this->getDoctrine()->getManager();
        $rubros = $em->getRepository('CYAYogaBundle:Rubro')->findAll();
        
        
        $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Usuario');
        $query = $repository->createQueryBuilder('u')
            ->where('u.rol != :rol')
            ->setParameter('rol', 'ROLE_USER')
            ->getQuery();
        $usuarios = $query->getResult();
        
        return $this->render('CYAYogaBundle:Movimiento:index.html.twig', array('pagination' => $pagination, 'rubros' => $rubros, 'usuarios' => $usuarios));
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
  */  
}
