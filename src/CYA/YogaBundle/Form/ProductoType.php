<?php

namespace CYA\YogaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class ProductoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descripcion',TextType::class)
            ->add('costo', MoneyType::class, array('currency' => '$'))
            ->add('preciolista', MoneyType::class, array('currency' => '$'))
            ->add('stock', NumberType::class)
            
            ->add('tipoproducto', EntityType::class, array(
                'class' => 'CYAYogaBundle:Tipoproducto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s');
                },
                'choice_label' => 'descripcion',  'placeholder' => 'Seleccione tipo de producto'
            ))
            ->add('proveedor', EntityType::class, array(
                'class' => 'CYAYogaBundle:Proveedor',
                'query_builder' => function (EntityRepository $es) {
                    return $es->createQueryBuilder('s');
                },
                'choice_label' => 'nombre',  'placeholder' => 'Seleccione Proveedor'
            ))
            
            ->add('isActive', CheckboxType::class, array('required' => false,
                                                         'data' => true,
                                                         'attr'     =>array ('class'=>'inputswitch','data-on-text'=>'ACTIVO','data-off-text'=>'INACTIVO'))) 
            ->add('save', SubmitType::class) 
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CYA\YogaBundle\Entity\Producto'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'cya_yogabundle_producto';
    }


}
