<?php

namespace CYA\YogaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormError;
use CYA\YogaBundle\Entity\Maestroventa;
use CYA\YogaBundle\Form\MaestroventaType;
use CYA\YogaBundle\Entity\Usuario;
use CYA\YogaBundle\Entity\Alumnocc;
use CYA\YogaBundle\Entity\AlumnoccType;
use CYA\YogaBundle\Form\UsuarioType;
use CYA\YogaBundle\Entity\Movimiento;
use CYA\YogaBundle\Form\MovimientoType;

class MaestroventaController extends Controller
{
    public function addAction(Request $request)
    {   
        $maestroventa = new Maestroventa();
        $form = $this->createForm(MaestroventaType::class, $maestroventa);
        $form->handleRequest($request);
        
        $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Usuario');
        $query = $repository->createQueryBuilder('u')
            ->where('u.isActive = :rol')
            ->setParameter('rol', 1)
            ->getQuery();
        $usuarios = $query->getResult();
        $usuarioQuery = $request->get('usuario'); 
        
        if ($form->isSubmitted() && $form->isValid())
        {
            
            
                $em = $this->getDoctrine()->getManager();
                $em->persist($maestroventa);
                $em->flush();
                $codigo = $maestroventa->getId();
                $total  = $maestroventa->getTotal();
                $this->addFlash('mensaje', 'Se generó la venta número: '.$codigo.' por un total de: $'.$total);
                
                
                /*actualiza el detalle con el maestro*/
                $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Detalleventa');
                $query = $repository->createQueryBuilder('m')
                ->where('m.maestroventa is NULL')
                ->getQuery();
                $detalleventa = $query->getResult();
        
                foreach($detalleventa as $det)
                {
                    $det->setMaestroventa($maestroventa);
               
                }
                
                     
                /*GRABA EN LA CAJA*/
                $tipo = $maestroventa->getTipopago();
                
                if($tipo == 'CO')
                {
                        $movimiento = new Movimiento();
                        $movimiento->setUsuario($this->get('security.token_storage')->getToken()->getUser());
                        $movimiento->setTipo('VP');
                        $movimiento->setFecha(new \DateTime("now"));
                        $movimiento->setMonto($total);
                        $movimiento->setDescripcion('VENTA DE PRODUCTOS');
                        $movimiento->setMaestroventa($maestroventa);
                        $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Rubro');
                        $rubro = $repository->findOneByNombre('venta de productos');
                        $movimiento->setRubro($rubro);
                        
                        $em->persist($movimiento);
                        $em->flush(); 
                        $this->addFlash('mensaje', 'Se actualizó la Caja +$'.$total);
                     
                }
                
                /*GRABA EN LA CC*/
                if($tipo == 'CC')
                {
                
                      if(empty($usuarioQuery))
                      {
                            $this->addFlash('mensaje', 'Si vende por cuenta corriente, debe elegir un usuario');
                      return $this->redirectToRoute('cya_maestroventa_add');
                      }
                        
                        $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Usuario');
                        $usuario = $repository->findOneById($usuarioQuery);
                        
                    $estadouser = $usuario->getIsActive();
                    
                    if ( $estadouser == 0)
                    {
                         $this->addFlash('mensaje', 'Este usuario se encuentra inactivo, imposible realizar la venta en cuenta');
                         return $this->redirectToRoute('cya_maestroventa_add');
                    }
                        
                        $alumnocc = new Alumnocc();
                        $alumnocc->setUsuario($usuario);
                        $alumnocc->setFechavencimiento(new \DateTime("now"));
                        $alumnocc->setFechamodificacion(new \DateTime("now"));
                        $alumnocc->setFechacreacion(new \DateTime("now"));
                        $alumnocc->setPagado(0);
                        $alumnocc->setMes('VTA. PRODUCTO');
                        $alumnocc->setBonificacion(0);
                        $alumnocc->setTipo('VP');
                        $alumnocc->setDeuda($total);
                        $alumnocc->setMaestroventa($maestroventa);
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($alumnocc);
                        $em->flush();
                      
                      $this->addFlash('mensaje', 'Se actualizó la Cuenta Corriente del Alumno N°: '. $usuarioQuery);
              }
                
                
                
                return $this->redirectToRoute('cya_maestroventa_add');
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
         
         
        return $this->render('CYAYogaBundle:Maestroventa:add.html.twig', array('form' => $form->createView(),'usuarios' => $usuarios,'saldo' => $saldo,'pagination' => $pagination));
    }
   
    public function agregarAction($id, $cantidad, Request $request){
        
        //$id, es el id del prodcuto seleccionado desde una lista
        //$cantidad es la cantidad del producto que se quiere agregar.
        
        $em = $this->getDoctrine()->getManager();
        $producto = $em->getRepository('CYAYogaBundle:Producto')->find($id);
        
        $auxiliarventa = new Auxiliarventaa();
        $auxiliarventa->setProducto($producto);
        $auxiliarventa->setCantidad($cantidad);
        $auxiliarventa->setMonto($cantidad * $producto->getPreciolista());
        $em->persist($auxiliarventa);
        $em->flush();
        
        $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Auxiliarventa');
        $auxiliares = $repository->findAll();
        
        return $this->render('CYAYogaBundle:Maestroventa:add.html.twig', array('form' => $form->createView(), 'auxiliares' => $auxiliares));
    }
     /*
    public function addAction($alumno, $tipo, $auxiliares, Request $request)
    {   
        $maestroventa = new Maestroventa();
        $form = $this->createForm(MaestroventaType::class, $maestroventa);
        $form->handleRequest($request);
        $costo = 0;
        
        if ($form->isSubmitted() && $form->isValid()){
            $maestroventa->setUsuario($this->get('security.token_storage')->getToken()->getUser());
            $maestroventa->setTipopago($tipo);
            $maestroventa->setFecha(new \DateTime("now"));
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($maestroventa);
            $em->flush();
            
            
            foreach($auxiliares as $aux){
            
                $detalleventa = new Detalleventa();
                $detalleventa->setMaestroventa($maestroventa);
                $detalleventa->setProducto($aux->getProducto());
                $detalleventa->setNombreProducto($aux->getProducto()->getDescripcion());
                $detalleventa->setPrecioproducto($aux->getProducto()->getPreciolista());
                $detalleventa->setCantidad($aux->getCantidad());
                $costo = $costo + $aux->getMonto();
                $em->persist($detalleventa);
                $em->flush();
              
            }
            
            $movimiento = new Movimiento();
            $movimiento->setUsuario($this->get('security.token_storage')->getToken()->getUser());
            $movimiento->setTipo('VP');
            $movimiento->setFecha(new \DateTime("now"));
            $movimiento->setMonto($costo);
            $movimiento->setDescripcion('VENTA DE PRODUCTOS');
            
            $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Rubro');
            $rubro = $repository->findOneByNombre('VENTA DE PRODUCTOS');
            if(!$rubro){
                $rubro = new Rubro();
                $rubro->setNombre('VENTA DE PRODUCTOS');
                $rubro->setTipo('C');
                $rubro->setIsActive(1);
                $em->persist($rubro);
                $em->flush(); 
                $movimiento->setRubro($rubro);
            }else{
                $movimiento->setRubro($rubro);
            }
            $em->persist($movimiento);
            $em->flush(); 
            
            $this->addFlash('mensaje', 'La venta ha sido efectuada');
            
            return $this->redirectToRoute('cya_movimiento_index');

        }
         
        return $this->render('CYAYogaBundle:Maestroventa:add.html.twig', array('form' => $form->createView()));
    }
    
    */
    

/* ESTO NO VA, EL LISTADO DE VENTAS LO TENES EN EL LSITADO DE MOVIMIENTOS
    public function indexAction(Request $request)
    {
        $searchQuery = $request->get('query');

        if(!empty($searchQuery)){
            $finder = $this->container->get('fos_elastica.finder.app.maestroventa');
            $tipoproductos = $finder->createPaginatorAdapter($searchQuery);
        }else{
            $em = $this->getDoctrine()->getManager();
            $dql = "SELECT r FROM CYAYogaBundle:Maestroventa r ORDER BY r.id DESC";
            $tipoproductos = $em->createQuery($dql);  
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $tipoproductos, $request->query->getInt('page' , 1),
            10
        );
        
        return $this->render('CYAYogaBundle:Maestroventa:index.html.twig', array('pagination' => $pagination, 'maestroventa' => $tipoproductos));
    }
    */
    

/* ESTO NO VA, NO VAS A NECESITAR EDIT
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
   */
}
