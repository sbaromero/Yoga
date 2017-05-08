<?php

namespace CYA\YogaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use CYA\YogaBundle\Entity\Locker;
use CYA\YogaBundle\Entity\Usuario;
use CYA\YogaBundle\Entity\Lockersocio;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class LockersocioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
             ->add('locker', EntityType::class, array('class' => 'CYAYogaBundle:Locker','query_builder' => function (EntityRepository $er) 
                    { return $er->createQueryBuilder('t')
                        ->where('t.isActive = :estado')
                        ->setParameter('estado', '0')
                        
                        ;},
                    'choice_label' => 'descripcion',
                    'placeholder'  => 'Seleccione un locker',
                ))
           
            ->add('fechainicio', DateType::class, array('widget' => 'single_text','attr' => ['class' => 'js-datepicker'],'data' => new \DateTime("now")))
            ->add('comentario',TextType::class)
            ->add('save', SubmitType::class)
            ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CYA\YogaBundle\Entity\Lockersocio'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'cya_yogabundle_lockersocio';
    }


}
