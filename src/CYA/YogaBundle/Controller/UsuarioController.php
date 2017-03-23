<?php

namespace CYA\YogaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormError;
use CYA\YogaBundle\Entity\Usuario;
use CYA\YogaBundle\Entity\Alumnocc;
use CYA\YogaBundle\Entity\Auxiliarventa;
use CYA\YogaBundle\Form\UsuarioType;
use CYA\YogaBundle\Form\UsuarioeditType;
use CYA\YogaBundle\Form\UsuariopublicType;
use CYA\YogaBundle\Form\UsuariorapidoType;
use CYA\YogaBundle\Form\UsuariopassType;
class UsuarioController extends Controller
{
    
    
    public function usuariorapidoAction(Request $request)
    {
        
        $usuario = new Usuario();
        
        $form = $this->createForm(UsuariorapidoType::class, $usuario);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $password = $form->get('password')->getData();
            
            $passwordConstraint = new Assert\NotBlank();
            $errorList = $this->get('validator')->validate($password, $passwordConstraint);
            
            if(count($errorList) == 0){
                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($usuario, $password);
                $usuario->setPassword($encoded);
                

                
                $em = $this->getDoctrine()->getManager();
                
                $fechahoy = new \DateTime("now");
                $usuario->setFechanacimiento($fechahoy);
                $usuario->setFechaingreso($fechahoy);
                $usuario->setFechareingreso($fechahoy);
                $usuario->setRol('ROLE_USER');
                $usuario->setDireccion('-');
                $usuario->setCiudad('-');
                $usuario->setTelefono('-');
                $usuario->setIsActive(1);
                $usuario->setMail('nombre@empresa');
                $em->persist($usuario);
                $em->flush();
                
                $this->crearCuentaCorriente($usuario, 'add');
                $this->addFlash('mensaje', 'El usuario ha sido creado, recuerde completar luego todos los datos');
                //$this->procesarCuentas();
                
                return $this->redirectToRoute('cya_usuario_index');
            }else{
                $errorMessege = new FormError($errorList[0]->getMessage());
                $form->get('password')->addError($errorMessege);
            }
        }
        
        return $this->render('CYAYogaBundle:Usuario:usuariorapido.html.twig', array('form' => $form->createView()));
    }
    
    
    public function inicioAction()
    {
        
            
     
            /* Usuarios*/
            $em = $this->getDoctrine()->getManager();
            $dql = "SELECT u FROM CYAYogaBundle:Usuario u WHERE u.id > 0";
            $dql = $dql . "AND u.isActive = 1";
            $users = $em->createQuery($dql); 
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate($users);
            $cantidada=$pagination->getTotalItemCount();
            $usuariosa=(string)$cantidada;
            
           
            $dql = "SELECT u FROM CYAYogaBundle:Usuario u WHERE u.id > 0";
            $dql = $dql . "AND u.isActive = 0";
            $users = $em->createQuery($dql); 
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate($users);
            $cantidadi=$pagination->getTotalItemCount();
            $usuariosi=(string)$cantidadi;
            
            $usuariost=(string)($cantidadi+$cantidada);
            
            
            /*productos*/
            $dql = "SELECT u FROM CYAYogaBundle:Producto u WHERE u.id > 0";
            $dql = $dql . "AND u.isActive = 1";
            $users = $em->createQuery($dql); 
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate($users);
            $cantidad2=$pagination->getTotalItemCount();
            $productos=(string)$cantidad2;
            
            /*lockers*/
            $dql = "SELECT u FROM CYAYogaBundle:Locker u WHERE u.id > 0";
            $users = $em->createQuery($dql); 
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate($users);
            $cantidad2=$pagination->getTotalItemCount();
            $lockers=(string)$cantidad2;
            
            
            
        $saldo = 0;  
        $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Producto');
        $query = $repository->createQueryBuilder('m')
        ->where('m.isActive = 1')
        ->getQuery();
        $detalleventa = $query->getResult();

        foreach($detalleventa as $det)
        {
            $saldo = $saldo + $det->getPreciolista();
       
        }
            $saldo= (string) $saldo;
            
            /*caja*/
            
                 
            /*caja*/
            
        $saldoc = 0;  

        $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Movimiento');
        $query = $repository->createQueryBuilder('m')
        ->orderBy('m.fecha', 'DESC')
        ->getQuery();
        $movimientos = $query->getResult();

        foreach($movimientos as $mov){
            if($mov->getRubro()->getTipo() == 'D'){
                $saldoc = $saldoc - $mov->getMonto();
            }else{
                $saldoc = $saldoc + $mov->getMonto();
            }
        }
            
             /*ctacte*/
      
        $dql = "SELECT a FROM CYAYogaBundle:Alumnocc a WHERE a.usuario in (SELECT u FROM CYAYogaBundle:Usuario u WHERE u.isActive = 1) ";
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
        
        $saldocc = $pagados + $bonificaciones -  $deudas;
        $pagados = $pagados + $bonificaciones;
       

           
               return $this->render('CYAYogaBundle:Usuario:home.html.twig',
               array ('deudas' => $deudas,
              'pagados' =>$pagados,
              'saldocc' =>$saldocc,
              'saldoc' => $saldoc,
              'lockers' => $lockers,
              'saldo' => $saldo,
              'usuariosa' => $usuariosa,
              'usuariosi' => $usuariosi,
              'usuariost' => $usuariost,
              'productos' => $productos ));
       
           } 
    
    public function homeAction()
    {
        $rol = $this->get('security.token_storage')->getToken()->getUser()->getRol();
        if ($rol != 'ROLE_USER')
        {
               
             $salida = exec('sudo /etc/init.d/elasticsearch start');
             $this->addFlash('mensaje', $salida);
            
            $this->procesarCuentas();
            
        
           /* Usuarios*/
            $em = $this->getDoctrine()->getManager();
            $dql = "SELECT u FROM CYAYogaBundle:Usuario u WHERE u.id > 0";
            $dql = $dql . "AND u.isActive = 1";
            $users = $em->createQuery($dql); 
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate($users);
            $cantidada=$pagination->getTotalItemCount();
            $usuariosa=(string)$cantidada;
            
           
            $dql = "SELECT u FROM CYAYogaBundle:Usuario u WHERE u.id > 0";
            $dql = $dql . "AND u.isActive = 0";
            $users = $em->createQuery($dql); 
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate($users);
            $cantidadi=$pagination->getTotalItemCount();
            $usuariosi=(string)$cantidadi;
            
            $usuariost=(string)($cantidadi+$cantidada);
            
            /* Productos*/
            $dql = "SELECT u FROM CYAYogaBundle:Producto u WHERE u.id > 0";
            $dql = $dql . "AND u.isActive = 1";
            $users = $em->createQuery($dql); 
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate($users);
            $cantidad2=$pagination->getTotalItemCount();
            $productos=(string)$cantidad2;
            
              /* lockers*/
            $dql = "SELECT u FROM CYAYogaBundle:Locker u WHERE u.id > 0";
            $users = $em->createQuery($dql); 
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate($users);
            $cantidad2=$pagination->getTotalItemCount();
            $lockers=(string)$cantidad2;
            
            
            /*stock*/
            $saldo = 0;  
            $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Producto');
            $query = $repository->createQueryBuilder('m')
            ->where('m.isActive = 1')
           ->getQuery();
            $detalleventa = $query->getResult();

            foreach($detalleventa as $det)
            {
              $saldo = $saldo + $det->getPreciolista();
       
            }
            $saldo= (string) $saldo;
            
            
            
        /*caja*/
            
        $saldoc = 0;  

        $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Movimiento');
        $query = $repository->createQueryBuilder('m')
        ->orderBy('m.fecha', 'DESC')
        ->getQuery();
        $movimientos = $query->getResult();

        foreach($movimientos as $mov){
            if($mov->getRubro()->getTipo() == 'D'){
                $saldoc = $saldoc - $mov->getMonto();
            }else{
                $saldoc = $saldoc + $mov->getMonto();
            }
        }
            
            
        /*ctacte*/
      
        $dql = "SELECT a FROM CYAYogaBundle:Alumnocc a WHERE a.usuario in (SELECT u FROM CYAYogaBundle:Usuario u WHERE u.isActive = 1) ";
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
        
        $saldocc = $pagados + $bonificaciones -  $deudas;
        $pagados = $pagados + $bonificaciones;
      
            
            
           
        }
        
        else
       {
        $pagados = 0;
        $deudas = 0;
        $bonificaciones = 0;
        $contador = 0;
        $saldocc = 0;
        $pagados = 0;
        $saldoc=0;
        $usuariosa = 0;
        $usuariosi =0;
        $usuariost =0;
        $productos =0;
        $lockers =0;
        $saldo =0;
         
       }
        
        
        return $this->render('CYAYogaBundle:Usuario:home.html.twig',
        array ('deudas' => $deudas,
              'pagados' =>$pagados,
              'saldocc' =>$saldocc,
              'saldoc' => $saldoc,
              'lockers' => $lockers,
              'saldo' => $saldo,
              'usuariosa' => $usuariosa,
              'usuariosi' => $usuariosi,
              'usuariost' => $usuariost,
              'productos' => $productos ));
    }
    
    public function indexAction(Request $request)
    {
        
       
            
        $searchQuery = $request->get('query');
        $estadoQuery = $request->get('estado');
        $tcQuery = $request->get('tipocuota');
        
        if(!empty($searchQuery)){
            $finder = $this->container->get('fos_elastica.finder.app.usuario');
            $users = $finder->createPaginatorAdapter($searchQuery);
        }else{
            $em = $this->getDoctrine()->getManager();
            $dql = "SELECT u FROM CYAYogaBundle:Usuario u WHERE u.id > 0";
        
            if ($estadoQuery == '1'){
                $dql = $dql . "AND u.isActive = 1";
                
            }
            
            if ($estadoQuery == '0'){
                $dql = $dql . "AND u.isActive = 0";
            }
            
            if(!empty($tcQuery)){
                $dql = $dql . " AND u.tipocuota = (SELECT t FROM CYAYogaBundle:Tipocuota t WHERE t.id = " . $tcQuery .")";
            }
            
            $dql = $dql . " ORDER BY u.id DESC";
            
            $users = $em->createQuery($dql); 
        }
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($users, $request->query->getInt('page' , 1),30);
        
        $em2 = $this->getDoctrine()->getManager();
        /*$tipocuotas = $em->getRepository('CYAYogaBundle:Tipocuota')->findAll();*/
        $tipocuotas = $em2->getRepository('CYAYogaBundle:Tipocuota')->findBy(array(), array('nombre' => 'ASC'));
        

        
        return $this->render('CYAYogaBundle:Usuario:index.html.twig',array('pagination' => $pagination, 'tipocuotas' => $tipocuotas));
        
    }
    
    public function addAction(Request $request)
    {
        $usuario = new Usuario();
        
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $password = $form->get('password')->getData();
            
            $passwordConstraint = new Assert\NotBlank();
            $errorList = $this->get('validator')->validate($password, $passwordConstraint);
            
            if(count($errorList) == 0){
                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($usuario, $password);
                $usuario->setPassword($encoded);
                
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($usuario);
                 $usuario->setIsActive(1);
                $em->flush();
                
                
                $this->crearCuentaCorriente($usuario, 'add');
                //$this->procesarCuentas();
                $this->addFlash('mensaje', 'El usuario ha sido creado');
                
                return $this->redirectToRoute('cya_usuario_index');
            }else{
                $errorMessege = new FormError($errorList[0]->getMessage());
                $form->get('password')->addError($errorMessege);
            }
        }
        
        return $this->render('CYAYogaBundle:Usuario:add.html.twig', array('form' => $form->createView()));
    }

    public function viewAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Usuario');
        
        $usuario = $repository->find($id);
        
       if(!$usuario){
           $messageException = 'Usuario no encontrado';
          throw $this->createNotFoundException($messageException);
       }
        
        
        $fecha = $usuario->getFechanacimiento();
        $fechas = $fecha->format('Y-m-d');
      /*  printf($fechas);*/
        list($Y,$m,$d) = explode("-",$fechas);
        $edad = date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y ;
    
        
        return $this->render('CYAYogaBundle:Usuario:view.html.twig', array('usuario' => $usuario, 'edad'=> $edad));
    }
    
   public function editAction($id, Request $request)
   {    
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository('CYAYogaBundle:Usuario')->find($id);
        $fechareingreso = $usuario->getFechareingreso();
        
        
        $form = $this->createForm(UsuarioeditType::class, $usuario);
    
        
        $form->handleRequest($request); 
        
        if(!$usuario){
            throw $this->createNotFoundException('Usuario no encontrado');
        }
       
        if ($form->isSubmitted() && $form->isValid()) {
            
             /*
            $nfechareingreso = $usuario->getFechareingreso();
            if($fechareingreso != $nfechareingreso)
            {
                $this->crearCuentaCorriente($usuario, 'edit');
            }
           
           
           
           
            $password = $form->get('password')->getData();
            
            if(!empty($password)){
                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($usuario, $password);
                $usuario->setPassword($encoded);
            }else{
                $recoverPass = $this->recoverPass($id);
                $usuario->setPassword($recoverPass[0]['password']); 
            }
           
            if(($form->get('rol')->getData() == 'ROLE_ADMIN')or($form->get('rol')->getData() == 'ROLE_SUPER')){
                $usuario->setIsActive(1);
            } */
            
            $em->flush();
            $this->procesarCuentas();
            $this->addFlash('mensaje', 'El usuario '.$usuario->getNombrecompleto().' ha sido modificado');
            
            return $this->redirectToRoute('cya_usuario_index');
        }                                                                                                                   
       
        return $this->render('CYAYogaBundle:Usuario:edit.html.twig', array('usuario' => $usuario, 'form' => $form->createView()));
   }
   
   public function editpublicAction(Request $request)
   {    
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->get('security.token_storage')->getToken()->getUser();

        $form = $this->createForm(UsuariopublicType::class, $usuario);
        $form->handleRequest($request); 
        

        if ($form->isSubmitted() && $form->isValid()) {
            
            $password = $form->get('password')->getData();
            
            if(!empty($password)){
                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($usuario, $password);
                $usuario->setPassword($encoded);
            }else{
                $recoverPass = $this->recoverPass($id);
                $usuario->setPassword($recoverPass[0]['password']); 
            }

            $em->flush();
            
            $this->addFlash('mensaje', 'El usuario ha sido modificado');
            
            return $this->render('CYAYogaBundle:Usuario:home.html.twig');
        }
       
        return $this->render('CYAYogaBundle:Usuario:editpublic.html.twig', array('usuario' => $usuario, 'form' => $form->createView()));
   }
   
   
    private function procesarCuentas(){
        
        $fechahoy = new \DateTime("now");
        $contador = 0;
        $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Usuario');
        $query = $repository->createQueryBuilder('u')
            ->where('u.isActive = 1')
            ->andWhere("u.rol = 'ROLE_USER'")
            ->getQuery();
        
        $usuarios = $query->getResult();
        $em = $this->getDoctrine()->getManager();
        foreach($usuarios as $us){
            if($us->getTipocuota()->getValor() > 0)
            {
                $generacc = 0;
                $tienecuenta = 0 ;
                $nogenerar = 0;
                $alumnoccs = $us->getAlumnoccs();
                
                $vencimiento = new \DateTime('2000-01-01 00:00:00');
                foreach($alumnoccs as $ccs)
                {
                     $tienecuenta = 1; //tiene al menos 1 cc
                     
                    if($ccs->getFechavencimiento() >= $fechahoy)
                    {
                        
                         $generacc  =1;  //la fecha vencimiento es mayor a hoy, no hay que generar
                         $nogenerar =1;
                         
                    }
                    
                    $vencimiento = $ccs->getFechavencimiento();
                 
                }
                
                if ($generacc == 0 &&  $tienecuenta == 0) // aun no tiene cuenta
                {
                    
                    $vencimiento =  $us->getFechareingreso();
                    $this->crearCuentaCorriente($us, 'edit');
                    break;
                    
                }
                  
                 date_add($vencimiento, date_interval_create_from_date_string ('1 month'));
                 
                 $mes = $vencimiento;
                 $mes = substr((string)$vencimiento->format('d/M/Y'), 3, 3);  
                 $mescorto= substr((string)$vencimiento->format('d/m/Y'), 3, 2);
                 $anio= substr((string)$vencimiento->format('d/m/Y'), 6, 4);
                 //  $this->addFlash('mensaje', $vencimiento);
                
                 $vencimiento = new \DateTime($anio.'-'.$mes.'-01'.' 00:00:00');
                
                
                 switch ($mes) 
                 {
                  case "Feb":
                  $mes = 'ENERO/'.$anio;
                  break;
                  case "Mar":
                  $mes = "FEBRERO/".$anio;
                  break;
                  case "Apr":
                  $mes = "MARZO/".$anio;
                  break;
                  case "May":
                  $mes = "ABRIL/".$anio;
                  break;
                  case "Jun":
                  $mes = "MAYO/".$anio;
                  break;
                  case "Jul":
                  $mes = "JUNIO/".$anio;
                  break;
                  case "Aug":
                  $mes = "JULIO/".$anio;
                  break;
                  case "Sep":
                  $mes = "AGOSTO/".$anio;
                  break;
                  case "Oct":
                  $mes = "SEPTIEMBRE/".$anio;
                  break;
                  case "Nov":
                  $mes = "OCTUBRE/".$anio;
                  break;
                  case "Dec":
                  $mes = "NOVIEMBRE/".$anio;
                  break;
                  case "Jan":
                  $mes = "DICIEMBRE/";
                  break;
                  }
                
                
                
             
                 
                if (($generacc == 0 && $tienecuenta == 0)  || ($nogenerar == 0   && $tienecuenta == 1)   )
                {
                    
                    $alumnocc = new Alumnocc();
                    $alumnocc->setUsuario($us);
                    $alumnocc->setFechavencimiento($vencimiento);
                    $alumnocc->setFechamodificacion(new \DateTime("now"));
                    $alumnocc->setFechacreacion(new \DateTime("now"));
                    $alumnocc->setPagado(0);
                    $alumnocc->setBonificacion(0);
                    $alumnocc->setTipo('PC');
                    $alumnocc->setDeuda($us->getTipocuota()->getValor());
                    $alumnocc->setMes($mes); 
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($alumnocc);
                    $em->flush(); 
                    $contador ++;
                }
            }
        }
        
        if ($contador > 0){
            $mensaje = 'Se acaban de generar ' . $contador . ' cuotas de usuario nuevas por vencimientos de cuotas del día de hoy.';
            if ($this->get('security.token_storage')->getToken()->getUser()->getRol() != 'ROLE_USER'){
                $this->addFlash('mensaje', $mensaje);
            }
        }
    }
    
    private function crearCuentaCorriente($usuario, $tipo)
    {
        // $this->corregirvencimientos();

        if ($usuario->getTipocuota()->getValor() > 0 and $usuario->getRol() == 'ROLE_USER' and $usuario->getIsActive()){
          //  $vencimiento = new \DateTime("now");
          //  if($tipo == 'edit'){
                $vencimiento = $usuario->getFechareingreso();
           // }
           
           
          $aniodic= substr((string)$vencimiento->format('d/m/Y'), 6, 4);
          date_add($vencimiento, date_interval_create_from_date_string('1 month'));
            
 
          $mes = $vencimiento;
          $mes = substr((string)$vencimiento->format('d/M/Y'), 3, 3);  
          $mescorto= substr((string)$vencimiento->format('d/m/Y'), 3, 2);
          $anio= substr((string)$vencimiento->format('d/m/Y'), 6, 4);
          //  $this->addFlash('mensaje', $vencimiento);
                
          $vencimiento = new \DateTime($anio.'-'.$mes.'-01'.' 00:00:00');
          
          
            
          switch ($mes) 
                 {
                  case "Feb":
                  $mes = 'ENERO/'.$anio;
                  break;
                  case "Mar":
                  $mes = "FEBRERO/".$anio;
                  break;
                  case "Apr":
                  $mes = "MARZO/".$anio;
                  break;
                  case "May":
                  $mes = "ABRIL/".$anio;
                  break;
                  case "Jun":
                  $mes = "MAYO/".$anio;
                  break;
                  case "Jul":
                  $mes = "JUNIO/".$anio;
                  break;
                  case "Aug":
                  $mes = "JULIO/".$anio;
                  break;
                  case "Sep":
                  $mes = "AGOSTO/".$anio;
                  break;
                  case "Oct":
                  $mes = "SEPTIEMBRE/".$anio;
                  break;
                  case "Nov":
                  $mes = "OCTUBRE/".$anio;
                  break;
                  case "Dec":
                  $mes = "NOVIEMBRE/".$anio;
                  break;
                  case "Jan":
                  $mes = "DICIEMBRE/".$aniodic;
                  break;
                  }
                
            
            
            $alumnocc = new Alumnocc();
            $alumnocc->setUsuario($usuario);
            $alumnocc->setFechavencimiento($vencimiento);
            $alumnocc->setFechamodificacion(new \DateTime("now"));
            $alumnocc->setFechacreacion(new \DateTime("now"));
            $alumnocc->setPagado(0);
            $alumnocc->setBonificacion(0);
            $alumnocc->setTipo('PC');
            $alumnocc->setDeuda($usuario->getTipocuota()->getValor());
            $alumnocc->setMes($mes); 
            $em = $this->getDoctrine()->getManager();
            $em->persist($alumnocc);
            $em->flush(); 
        }
    }
    
   public function corregirvencimientos ()
    {
        
        
        $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Alumnocc');
        $query = $repository->createQueryBuilder('u')
            ->where("u.tipo = 'PC'")
            ->getQuery();
        

        $alumnoccs = $query->getResult();
        $em = $this->getDoctrine()->getManager();

                foreach($alumnoccs as $ccs)
                {
                  
                   $venci2 = $ccs->getFechavencimiento();    
                   $year  = $venci2;
                   $month = $venci2;
                   $year  = date('Y');
                   $month = date('m'); 
                   $venci  = new \DateTime("$year-$month-01");   
                   
                 //$venci2->modify('first day of this month');
                 //$venci  = new \DateTime('first day of this month');   
                 //$venci=$venci2;     
                   
                   
                    $ccs->setFechavencimiento($venci);
                    $em->persist($ccs);
                    $em->flush(); 
                    
                  //$venci = $ccs->getFechavencimiento() ;
                  // $vencimiento = (string)$venci->format('d/m/Y');
                  // $this->addFlash('mensaje', $vencimiento);
                    
                }
                
               
                
        
    }



    
  
   public function construAction(Request $request)
    {
        return $this->render('CYAYogaBundle:Usuario:constru.html.twig');
    }

    
    
     public function editpassAction($id, Request $request)
   {    
        $em = $this->getDoctrine()->getManager();
              
        $usuario = $em->getRepository('CYAYogaBundle:Usuario')->find($id);
       
             


        
        $form = $this->createForm(UsuariopassType::class, $usuario);
    
        
        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) 
        {
            
            
            $password = $form->get('password')->getData();
            
            $passwordConstraint = new Assert\NotBlank();
            $errorList = $this->get('validator')->validate($password, $passwordConstraint);
            
            if(count($errorList) == 0)
            {
                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($usuario, $password);
                $usuario->setPassword($encoded);
                
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($usuario);
        
           
           
            $em->flush();
           
            $this->addFlash('mensaje', 'El password usuario '.$usuario->getNombrecompleto().' ha sido modificado');
            
            }
            return $this->redirectToRoute('cya_usuario_index');
        }                                                                                                                   
      
        return $this->render('CYAYogaBundle:Usuario:editpass.html.twig', array('usuario' => $usuario, 'form' => $form->createView()));
    
   }
    
}
