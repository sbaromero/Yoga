<?php

namespace CYA\YogaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use CYA\YogaBundle\Entity\Usuario;
use CYA\YogaBundle\Entity\Locker;
use CYA\YogaBundle\Entity\Lockersocio;
use CYA\YogaBundle\Form\LockersocioType;


class LockersocioController extends Controller
{
    
    //****************************INDEX************************************** 
    public function indexAction(Request $request)
    {    
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT u FROM CYAYogaBundle:Lockersocio u WHERE u.id > 0";
        $lockers = $em->createQuery($dql);  

      $paginator = $this->get('knp_paginator');
      $pagination = $paginator->paginate($lockers, $request->query->getInt('page' , 1),
           10
       );


        return $this->render('CYAYogaBundle:Lockersocio:index.html.twig',array('lockers' => $lockers,'pagination' => $pagination,));
    }
    
   
    //**************************ADD*************************************************
    
        public function addAction(Request $request)
    {   
        
         
        $lockersocio = new Lockersocio();
        $form = $this->createForm(LockersocioType::class, $lockersocio);
        $form->handleRequest($request);
        
        $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Usuario');
        $query = $repository->createQueryBuilder('u')
            ->where('u.isActive = :rol')
            ->setParameter('rol', 1)
            ->getQuery();
        $usuarios = $query->getResult();
        $usuarioQuery = 0;
        $usuarioQuery = $request->get('usuario'); 
        
        if ($form->isSubmitted() && $form->isValid())
        {
            
                $em = $this->getDoctrine()->getManager();
                $usuario = $repository->findOneById($usuarioQuery);
                
                if  ($usuario->gethaveLocker() == 1){
                    
                     $this->addFlash('mensaje', 'Este usuario ya tiene un locker asignado: '.$usuario->getnombrecompleto() );
                     return $this->redirectToRoute('cya_lockersocio_index');
                
                
                    
                }
                
                  
              if ($usuarioQuery == 0)
              { 
             
                 return $this->redirectToRoute('cya_lockersocio_add');
                
                 
             
               }
                
                $usuario->sethaveLocker(1);
                
                $lockersocio->setUsuario($usuario);
                $em->persist($lockersocio);
                $em->flush();
               
                $lock =  $lockersocio->getlocker()->getId();
                $repository2 = $this->getDoctrine()->getRepository('CYAYogaBundle:Locker');
                $query2 = $repository2->createQueryBuilder('u')
                 ->where('1 = 1')
                 ->getQuery();
                $locks = $query2->getResult();
                $lockelegido = $repository2->findOneById($lock);
                $lockelegido->setIsActive(1);
                $em->persist($lockelegido);
                $em->flush();
                
                $codigo = $lockersocio->getId();
                
               // $this->addFlash('mensaje',  $lock );
                $this->addFlash('mensaje', 'Se generó la asignación de locker al usuario: '.$usuario->getnombrecompleto() );
               // $this->addFlash('mensaje', 'REVISAR TIPO DE CUOTA (ELEGIR UN  TIPO DE CUOTA + CASILLERO/LOCKER) PARA: '.$usuario->getnombrecompleto());
        
               
                return $this->redirectToRoute('cya_lockersocio_index');
        }
         
        
      
     //    $paginator = $this->get('knp_paginator');
     //    $pagination = $paginator->paginate($lockersocio, $request->query->getInt('page', 1), 5);
         
         
        return $this->render('CYAYogaBundle:Lockersocio:add.html.twig', array('usuarios' => $usuarios,'form' => $form->createView()));
 
    }
   
    
    
    public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $locker = $em->getRepository('CYAYogaBundle:Lockersocio')->find($id);
        $lock =  $locker->getlocker()->getId(); 
        $user =  $locker->getUsuario();
        
        
        $repository = $this->getDoctrine()->getRepository('CYAYogaBundle:Usuario');
        $query = $repository->createQueryBuilder('u')
            ->where('1 = 1')
            ->getQuery();
        $usuarios = $query->getResult();
        $usuarioQuery = 0;
        $usuarioQuery = $request->get('usuario'); 
        $usuario = $repository->findOneById($user);
        $usuario->sethaveLocker(0);
        
        
        
        $em->remove($locker);
        $em->flush();  
        $successMessage = 'Asignación de Locker eliminada';
        $this->addFlash('mensaje', $successMessage);
        
       
       $repository2 = $this->getDoctrine()->getRepository('CYAYogaBundle:Locker');
        $query2 = $repository2->createQueryBuilder('u')
                 ->where('1 = 1')
                 ->getQuery();
        $locks = $query2->getResult();
        $lockelegido = $repository2->findOneById($lock);
        $lockelegido->setIsActive(0);
        $em->persist($lockelegido);
        $em->flush();
        

        return $this->redirectToRoute('cya_lockersocio_index'); 
    }
    
  
    
}
