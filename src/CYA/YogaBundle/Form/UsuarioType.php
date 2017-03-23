<?php

namespace CYA\YogaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use CYA\YogaBundle\Entity\Tipocuota;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class UsuarioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombreusuario',TextType::class)
            ->add('nombre',TextType::class)
            ->add('apellido',TextType::class)
            ->add('dni',TextType::class)
            ->add('direccion',TextType::class)
            ->add('ciudad',TextType::class)
            ->add('telefono',TextType::class)
            ->add('mail', EmailType::class)
            ->add('fechanacimiento', DateType::class, array('widget' => 'single_text','attr' => ['class' => 'js-datepicker'],))
            ->add('fechaingreso', DateType::class, array('widget' => 'single_text','attr' => ['class' => 'js-datepicker'],'data' => new \DateTime("now")))
            ->add('fechareingreso', DateType::class, array('widget' => 'single_text','attr' => ['class' => 'js-datepicker'],'data' => new \DateTime("now")))
            ->add('password', PasswordType::class)
            ->add('rol', ChoiceType::class, array('choices' => array('Superadmin' => 'ROLE_SUPER', 'Administrador' => 'ROLE_ADMIN', 'Usuario' => 'ROLE_USER' ), 'placeholder' => 'Seleccione un rol'))
            ->add('isActive', CheckboxType::class, array('required' => false,
                                                         'data'     => true,
                                                         'attr'     =>array ('class'=>'inputswitch','data-on-text'=>'ACTIVO','data-off-text'=>'INACTIVO'))) 
            ->add('tipocuota', EntityType::class, array('class' => 'CYAYogaBundle:Tipocuota','query_builder' => function (EntityRepository $er) 
                    { return $er->createQueryBuilder('t')
                        ->where('1 = 1');},
                    'choice_label' => 'nombre',
                    'placeholder'  => 'Seleccione un tipo de cuota',
                ))
            
            ->add('save', SubmitType::class)   
        ; 
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CYA\YogaBundle\Entity\Usuario'
        ));
    }

    /**
     * {@inheritdoc}
     */
     
    public function getBlockPrefix()
    {
        return 'cya_yogabundle_usuario';
    }


}
