<?php

namespace CYA\YogaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use CYA\YogaBundle\Entity\Usuario;
use CYA\YogaBundle\Entity\Rubro;
use CYA\YogaBundle\Entity\Alumnocc;
use CYA\YogaBundle\Entity\Movimiento;
use CYA\YogaBundle\Entity\Producto;
use CYA\YogaBundle\Entity\Maestroventa;
use CYA\YogaBundle\Form\AlumnoccType;
use CYA\YogaBundle\Form\PagodiarioType;
use CYA\YogaBundle\Form\GeneraccType;

class AlumnoccController extends Controller
{
    
  
    public function generaccAction(Request $request)
    {   
        
       
        $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Usuario');
        $query = $repository->createQueryBuilder('u')
            ->where('u.rol = :rol')
            ->setParameter('rol', 'ROLE_USER')
            ->getQuery();
        $usuarios = $query->getResult();
        
        $alumnocc = new Alumnocc();
        $form = $this->createForm(GeneraccType::class, $alumnocc);
        $form->handleRequest($request); 
        $userid = $request->get('usuario'); 
        
        $fechaCreac= new \DateTime("now");
        $mes = 'Ver Comentario';
        
        if ($form->isSubmitted() && $form->isValid()) 
        {
           
        $tipocc = $userid = $request->get('tipocc'); 
        $mes = $userid = $request->get('mes'); 
        $anio = $userid = $request->get('año'); 
       
        switch ($mes){
        case "1":
            $mesreal="Diciembre";
            break;
            case "2":
            $mesreal="Enero";
            break;
             case "3":
            $mesreal="Febrero";
            break;
             case "4":
            $mesreal="Marzo";
            break;
             case "5":
            $mesreal="Abril";
            break; 
            case "6":
            $mesreal="Mayo";
            break;
             case "7":
            $mesreal="Junio";
            break;
             case "8":
            $mesreal="Julio";
            break;
             case "9":
            $mesreal="Agosto";
            break;
             case "10":
            $mesreal="Septiembre";
            break; 
            case "11":
            $mesreal="Octubre";
            break;
            case "12":
            $mesreal="Noviembre";
            break;
        }
        
        
    if  ($mes == 1) {
        
        $anio = $anio + 1;
    }   
        
            $mesreal=strtoupper($mesreal); 
           
           $fechavenc = new \DateTime($anio.'-'.$mes.'-01'.' 00:00:00');
            
 if ($tipocc == 'PC' ){        
    $mesSet =   $mesreal.'/'. $anio;  
 }     else{
      $mesSet =   'CC NORMAL'; 
 }
            if ($userid != null){
            $userid = $request->get('usuario'); 
            $userelegido = $repository->findOneById($userid);

            $alumnocc->setUsuario($userelegido);
            $alumnocc->setPagado(0);
            $alumnocc->setBonificacion(0);
            $alumnocc->setMes($mesSet);
            $alumnocc->setFechavencimiento($fechavenc);
            $alumnocc->setFechamodificacion(new \DateTime("now"));
            $alumnocc->setFechacreacion($fechaCreac);
            $alumnocc->setTipo($tipocc);
           
            $em = $this->getDoctrine()->getManager();
            $em->persist($alumnocc);
            $em->flush();
            
            $this->addFlash('mensaje', 'El movimiento ha sido generado al usuario:'. $userelegido->getnombrecompleto());
                
            return $this->redirectToRoute('cya_alumnocc_index', array('usuario'=> $userid));
            }
            
      
          }
         
          return $this->render('CYAYogaBundle:Alumnocc:generacc.html.twig', array('usuarios'=>$usuarios,'form' => $form->createView()));
            
        }
        
     
    
    public function indexdeudaAction(Request $request)
    {
        
        $dcQuery = $request->get('dc');
        $acQuery = $request->get('ac');
       
        
        
        $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Tipocuota');
        $query = $repository->createQueryBuilder('u')
            ->where('1 = 1')
            ->orderBy('u.nombre','ASC')
            ->getQuery();
        $cuotas = $query->getResult();
     
        $cuota = 0;
        $cuotaQuery = $request->get('cuota'); 
     
        $cuotaelegida = $repository->findOneById($cuotaQuery);
          
        $nombrecuota = 'Elija un tipo de cuota';
      
        $em = $this->getDoctrine()->getManager();
     
       if (empty($cuotaQuery)) 
        {
         $dql = "SELECT a from CYAYogaBundle:Alumnocc a WHERE 1=2 ";
          $this->addFlash('mensaje', 'Elija un tipo de cuota');
       }
          else
        {
         $dql = "SELECT a from CYAYogaBundle:Alumnocc a join CYAYogaBundle:Usuario b ";
         $nombrecuota = $cuotaelegida->getnombre();
       //    $dql = $dql . "AND  (a.deuda - (a.pagado + a.bonificacion) > 0) ";
        $dql = $dql . " WHERE  a.usuario = b.id";
        $dql = $dql . " AND  b.tipocuota =" .  $cuotaQuery ;
        
         if(!empty($dcQuery) && ($dcQuery == 'Deudores'))
         {
            
           $dql = $dql . "AND  (a.deuda - (a.pagado + a.bonificacion) > 0) ";
        }
        
         if(!empty($acQuery))
         {
         
             if ($acQuery == 'Activos')
             
             {
              
                 $dql = $dql . "AND  b.isActive = 1 ";
             
             }
             else
             {  
            
                $dql = $dql . "AND  b.isActive = 0 ";
                 
             }
         }
        
        $dql = $dql . " ORDER  BY b.apellido ";
        }
        
        $nombrecuota= $nombrecuota . "-" . $dcQuery . "-".  $acQuery;
        
        $usuarios = $em->createQuery($dql); 

        $contador = 0;
        $deudas  = 0;
        $pagados  = 0;
        $bonificaciones  = 0;
        $saldo=0;
         
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
        $usuarios, $request->query->getInt('page' , 1),
        40000
        );
        
        return $this->render('CYAYogaBundle:Alumnocc:indexdeuda.html.twig',
        array('pagination' => $pagination, 
        'usuarios' => $usuarios, 
        'contador' => $contador,
        'deudas' => $deudas,
        'bonificaciones' => $bonificaciones,
        'saldo' => $saldo,
        'pagados' => $pagados,
        'cuotas' => $cuotas,
        'nombrecuota' =>$nombrecuota
        ));
        
        
    }

    
    
    
    
    public function indexAction(Request $request)
    {   
        
        /*$this->addFlash('mensaje', 'Pago de cuotas');*/
        $usuarioQuery = $request->get('usuario'); 
        $pagoQuery = $request->get('pago'); 

        $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Usuario');
        $query = $repository->createQueryBuilder('u')
            ->where('u.rol = :rol')
            ->setParameter('rol', 'ROLE_USER')
            ->getQuery();
        $usuarios = $query->getResult();

        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT a FROM CYAYogaBundle:Alumnocc a WHERE a.usuario in (SELECT u FROM CYAYogaBundle:Usuario u WHERE u.isActive = 1) ";
        
        if(!empty($usuarioQuery)){
            $dql = $dql . "AND a.usuario = (SELECT f FROM CYAYogaBundle:Usuario f WHERE f.id = " . $usuarioQuery .") ";
        }
        if(!empty($pagoQuery)){
            if($pagoQuery == 'S'){
                $dql = $dql . "AND a.deuda <= a.pagado + a.bonificacion ";
            }
            if($pagoQuery == 'N'){
                $dql = $dql . "AND a.deuda > a.pagado + a.bonificacion ";
            }
        }
        
         $dql = $dql . "AND a.deuda > 0  ";
        
        $dql = $dql . "ORDER BY a.id DESC";
        $alumnoccs = $em->createQuery($dql); 
        
        $alumnoccs2 =  $alumnoccs->getResult();
        $pagados = 0;
        $deudas = 0;
        $bonificaciones = 0;
        $contador = 0;
        foreach($alumnoccs2 as $alu)
        {
            
           $pagados = $pagados +  $alu->getPagado();
           $deudas = $deudas + $alu->getDeuda();
           $bonificaciones = $bonificaciones + $alu->getBonificacion();
           $contador=$contador+1;
            
        }
        
        $saldo = $pagados + $bonificaciones -  $deudas;
        $alumnoccs = $em->createQuery($dql); 
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($alumnoccs, $request->query->getInt('page' , 1),50);
        
 
 
        return $this->render('CYAYogaBundle:Alumnocc:index.html.twig',array('contador' => $contador,'saldo' => $saldo,'pagados' => $pagados,'deudas' => $deudas,'bonificaciones'=>$bonificaciones,'pagination' => $pagination, 'usuarios' => $usuarios ));
    }
    
    public function indexpublicAction(Request $request)
    {
        $id = $this->get('security.token_storage')->getToken()->getUser()->getId();
        
        $pagoQuery = $request->get('pago'); 

        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT a FROM CYAYogaBundle:Alumnocc a WHERE a.usuario in (SELECT u FROM CYAYogaBundle:Usuario u WHERE u.id =" . $id .")";
        
        if(!empty($pagoQuery)){
            if($pagoQuery == 'S'){
                $dql = $dql . "AND a.deuda <= a.pagado + a.bonificacion ";
            }
            if($pagoQuery == 'N'){
                $dql = $dql . "AND a.deuda > a.pagado + a.bonificacion ";
            }
        }
        
        $dql = $dql . "ORDER BY a.id DESC";
        $alumnoccs = $em->createQuery($dql); 
        
      
      
       $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Usuario');
        $query = $repository->createQueryBuilder('u')
            ->where('u.rol = :rol')
            ->setParameter('rol', 'ROLE_USER')
            ->getQuery();
        $usuarios = $query->getResult();
      
      
      
        
        $alumnoccs2 =  $alumnoccs->getResult();
        $pagados = 0;
        $deudas = 0;
        $bonificaciones = 0;
        $contador = 0;
        foreach($alumnoccs2 as $alu)
        {
            
           $pagados = $pagados +  $alu->getPagado();
           $deudas = $deudas + $alu->getDeuda();
           $bonificaciones = $bonificaciones + $alu->getBonificacion();
           $contador=$contador+1;
            
        }
        
        $saldo = $pagados + $bonificaciones -  $deudas;
        $alumnoccs = $em->createQuery($dql); 
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
        $alumnoccs, $request->query->getInt('page' , 1),
           40
        );
        
        
       return $this->render('CYAYogaBundle:Alumnocc:indexpublic.html.twig',array('contador' => $contador,'saldo' => $saldo,'pagados' => $pagados,'deudas' => $deudas,'bonificaciones'=>$bonificaciones,'pagination' => $pagination, 'usuarios' => $usuarios ));
  
        
    }



    public function pagoAction($id, Request $request)
    {    
        $em = $this->getDoctrine()->getManager();
        $alumnocc = $em->getRepository('CYAYogaBundle:Alumnocc')->find($id);
        $nombrecompleto = $alumnocc->getUsuario()->getNombrecompleto();
        $fechavencimiento = $alumnocc->getUsuario()->getfechareingreso();
        $vencimiento = (string)$fechavencimiento->format('d/m/Y');
        $saldo = $alumnocc->getDeuda() - $alumnocc->getPagado() - $alumnocc->getBonificacion();
        $pagado = $alumnocc->getPagado();
        $bonif = $alumnocc->getBonificacion();
        $alumnocc->setPagado(0);
        $usuario=$alumnocc->getUsuario();
        
        $form = $this->createForm(AlumnoccType::class, $alumnocc);
        $form->handleRequest($request); 
     
        
        if(!$alumnocc){
            throw $this->createNotFoundException('La cuota no existe');
        }
       
        if ($form->isSubmitted() && $form->isValid()) {
            
            $movimiento = new Movimiento();
            $movimiento->setTipo('CC');
            $movimiento->SetDescripcion('Pago de cuotas');
            
            $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Rubro');
            $rubro = $repository->findOneByNombre('PAGO CC');
            if(!$rubro){
                $rubro = new Rubro();
                $rubro->setNombre('PAGO CC');
                $rubro->setTipo('C');
                $rubro->setIsActive(1);
                $em->persist($rubro);
                $em->flush(); 
                $movimiento->setRubro($rubro);
            }else{
                $movimiento->setRubro($rubro);
            }
            $movimiento->setUsuario($this->get('security.token_storage')->getToken()->getUser());
            $movimiento->setFecha(new \DateTime("now"));
            
            $monto = $alumnocc->getPagado();
            $bonificado = $alumnocc->getBonificacion();
            
            $alumnocc->setFechamodificacion(new \DateTime("now"));
            $alumnocc->setFechacreacion(new \DateTime("now"));
            $alumnocc->setTipo('CC');
            $alumnocc->setPagado($pagado + $alumnocc->getPagado());
            $em->flush();
            
            $movimiento->setMonto($alumnocc->getPagado()-$pagado);
            $movimiento->setAlumnocc($alumnocc);
            $em->persist($movimiento);
            $em->flush(); 
            
            $mensajepagar =  'El pago ha sido efectuado';
            $this->addFlash('mensaje',$mensajepagar);
            
            $idusuario = $alumnocc->getUsuario()->getid();
            return $this->redirectToRoute('cya_alumnocc_index', array('usuario' => $idusuario));
        }
         
         
     
        return $this->render('CYAYogaBundle:Alumnocc:pago.html.twig', array('pagado'=>$pagado, 'usuario'=>$usuario,  'bonif' => $bonif,'saldo' => $saldo,'nombrecompleto' => $nombrecompleto, 
        'vencimiento' => $vencimiento,'alumnocc' => $alumnocc, 'form' => $form->createView()));
   }
    public function pagodiarioAction(Request $request)
    {   
        $alumnocc = new Alumnocc();
        $form = $this->createForm(PagodiarioType::class, $alumnocc);
        $form->handleRequest($request); 
        
        if ($form->isSubmitted() && $form->isValid()) {
            $alumnocc->setDeuda($alumnocc->getPagado());
            $alumnocc->setBonificacion(0);
            $alumnocc->setFechavencimiento(new \DateTime("now"));
            $alumnocc->setFechamodificacion(new \DateTime("now"));
            $alumnocc->setFechacreacion(new \DateTime("now"));
            $alumnocc->setTipo('PD');
            $alumnocc->SetComentario('Pago diario clase de yoga');
            $em = $this->getDoctrine()->getManager();
            $em->persist($alumnocc);
            $em->flush(); 
            
            $movimiento = new Movimiento();
            
            $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Rubro');
            $rubro = $repository->findOneByNombre('PAGO CC');
            if(!$rubro){
                $rubro = new Rubro();
                $rubro->setNombre('PAGO CC');
                $rubro->setTipo('C');
                $rubro->setIsActive(1);
                $em->persist($rubro);
                $em->flush(); 
                $movimiento->setRubro($rubro);
            }else{
                $movimiento->setRubro($rubro);
            }
            $movimiento->setTipo('CC');
            $movimiento->SetDescripcion('Pago diario clase de yoga');
            $movimiento->setUsuario($this->get('security.token_storage')->getToken()->getUser());
            $movimiento->setFecha(new \DateTime("now"));
            $movimiento->setMonto($alumnocc->getPagado());
            $movimiento->setAlumnocc($alumnocc);
            
            $em->persist($movimiento);
            $em->flush(); 
            
            $this->addFlash('mensaje', 'El pago ha sido efectuado');
            
            return $this->redirectToRoute('cya_alumnocc_index');
            
        }
        
        return $this->render('CYAYogaBundle:Alumnocc:pagodiario.html.twig', array('form' => $form->createView()));
    }
    
    public function detallepagoAction($id, Request $request)
    {
        if(!empty($id)){
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT m FROM CYAYogaBundle:Movimiento m WHERE m.alumnocc in (SELECT a FROM CYAYogaBundle:Alumnocc a WHERE a.id = " . $id . ")";
        $movimientos = $em->createQuery($dql);
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $movimientos, $request->query->getInt('page' , 1),
           5
        );
        
        return $this->render('CYAYogaBundle:Alumnocc:detallepago.html.twig',array('pagination' => $pagination));
        }else{
            $this->addFlash('mensaje', 'El detalle no está disponible');
            return $this->redirectToRoute('cya_alumnocc_index');
        }
    }
    
    public function detallepagopublicAction($id, Request $request)
    {
        if(!empty($id)){
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT m FROM CYAYogaBundle:Movimiento m WHERE m.alumnocc in (SELECT a FROM CYAYogaBundle:Alumnocc a WHERE a.id = " . $id . ")";
        $movimientos = $em->createQuery($dql);
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $movimientos, $request->query->getInt('page' , 1),
           5
        );
        
        return $this->render('CYAYogaBundle:Alumnocc:detallepagopublic.html.twig',array('pagination' => $pagination));
        }else{
            $this->addFlash('mensaje', 'El detalle no está disponible');
            return $this->redirectToRoute('cya_alumnocc_indexpublic');
        }
    }
    
    public function deleteAction($id, Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        $alumnocc = $em->getRepository('CYAYogaBundle:Alumnocc')->find($id);
       
        if(!$alumnocc)
        {
            throw $this->createNotFoundException('CC no encontrada');
        }   
        
       
        if($alumnocc->getTipo() == 'VP')
        {
           
            $maestroventa = new Maestroventa();
            $maestroventa = $alumnocc->getMaestroventa();
            
            $detalleventas = $maestroventa->getDetalleventas();
            
         foreach($detalleventas as $detalle)
            {
            
                $producto = new Producto();
                $producto = $detalle->getProducto();
                $producto->setStock($producto->getStock() + $detalle->getCantidad());
                $em->flush();

                $em->remove($detalle);
                $em->flush();
                
            }
          
            $em->remove($maestroventa);
            $em->remove($alumnocc);
            $em->flush(); 
        }

        if($alumnocc->getTipo() == 'PC')
        {
           $em->remove($alumnocc);
           $em->flush();  
        }
        
        if($alumnocc->getTipo() == 'GE')
        {
           $em->remove($alumnocc);
           $em->flush();  
        }
        
        
         if($alumnocc->getTipo() == 'CC' || $alumnocc->getTipo() == 'PD' )
        {
           
            $movimiento = new Movimiento();
          
            $rubro = $em->getRepository('CYAYogaBundle:Rubro')->find(8);
            if(!$rubro){
                $rubro = new Rubro();
                $rubro->setNombre('CORRECCION DE CAJA (DEBITO)');
                $rubro->setTipo('D');
                $rubro->setIsActive(1);
                $em->persist($rubro);
                $em->flush(); 
                $movimiento->setRubro($rubro);
            }else{
                $movimiento->setRubro($rubro);
            }
            $movimiento->setTipo('CC');
            $movimiento->SetDescripcion('DEBITO DE CAJA POR CORRECCION EN CUENTAS CORRIENTES');
            $movimiento->setUsuario($this->get('security.token_storage')->getToken()->getUser());
            $movimiento->setFecha(new \DateTime("now"));
            $movimiento->setMonto($alumnocc->getPagado());
            $movimiento->setAlumnocc($alumnocc);
            $em->persist($movimiento);
            $em->flush(); 
            $this->addFlash('mensaje', 'Ha sido actualizada la caja');
           
        $em2 = $this->getDoctrine()->getManager();
        $mov = $em2->getRepository('CYAYogaBundle:Alumnocc')->find($id);

        
        $em = $this->getDoctrine()->getManager();
        $alumnocc = $em->getRepository('CYAYogaBundle:Alumnocc')->find($id);
        $alumnocc->setPagado(0);
        $alumnocc->setDeuda(0);
        $alumnocc->setBonificacion(0);
        $alumnocc->setcomentario('CC ELIMINADA');
         
        $em->flush();
         
           
        }
        
        
        $successMessage = 'CC eliminada';
        $this->addFlash('mensaje', $successMessage);

        $idusuario = $alumnocc->getUsuario()->getid();
            return $this->redirectToRoute('cya_alumnocc_index', array('usuario' => $idusuario));
    }
    
    public function detailsAction($id, Request $request)
    {
        if(!empty($id)){
            $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Alumnocc');
            $alumnocc = $repository->findOneById($id);
            $maestroventa = $alumnocc->getMaestroventa();
            $detalleventas = $maestroventa->getDetalleventas();

            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $detalleventas, $request->query->getInt('page' , 1),
               15
            );
            
            $fechaventa = (string)$maestroventa->getFecha()->format('d/m/Y');
            return $this->render('CYAYogaBundle:Alumnocc:details.html.twig',array('pagination' => $pagination, 'fecha' => $fechaventa, 'total' => $maestroventa->getTotal()));
        }else{
            $this->addFlash('mensaje', 'El detalle no está disponible');
            return $this->redirectToRoute('cya_alumnocc_index');
        }
    }
      
      
   
  
    
    
}
