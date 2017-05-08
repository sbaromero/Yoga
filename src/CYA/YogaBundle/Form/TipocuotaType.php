<?php

namespace CYA\YogaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class TipocuotaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre',TextType::class)
            ->add('valor',MoneyType::class, array('currency' => 'ARS'))
            // ->add('instructorado', CheckboxType::class, array('required' => false, 'attr' => array ('class'=>'inputswitch','data-on-text'=>'SI','data-off-text'=>'NO'))) 
            // ->add('clasesyoga', CheckboxType::class, array('required' => false, 'attr' => array ('class'=>'inputswitch','data-on-text'=>'SI','data-off-text'=>'NO'))) 
            // ->add('asociacion', CheckboxType::class, array('required' => false, 'attr' => array ('class'=>'inputswitch','data-on-text'=>'SI','data-off-text'=>'NO'))) 
            // ->add('profesorado', CheckboxType::class, array('required' => false, 'attr' => array ('class'=>'inputswitch','data-on-text'=>'SI','data-off-text'=>'NO'))) 
            // ->add('posgrado', CheckboxType::class, array('required' => false, 'attr' => array ('class'=>'inputswitch','data-on-text'=>'SI','data-off-text'=>'NO'))) 
            // ->add('casillero', CheckboxType::class, array('required' => false, 'attr' => array ('class'=>'inputswitch','data-on-text'=>'SI','data-off-text'=>'NO'))) 
            // ->add('otro', CheckboxType::class, array('required' => false, 'attr' => array ('class'=>'inputswitch','data-on-text'=>'SI','data-off-text'=>'NO'))) 
            ->add('save', SubmitType::class)
            ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CYA\YogaBundle\Entity\Tipocuota'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'cya_yogabundle_tipocuota';
    }


}
