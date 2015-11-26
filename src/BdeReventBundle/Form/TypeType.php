<?php

namespace BdeReventBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Created by PhpStorm.
 * User: pvienne
 * Date: 25/11/15
 * Time: 00:15
 */

class TypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name')
            ->add('canInvite', 'checkbox', array('attr' => array('align_with_widget' => true),
                'required' => false))
            ->add('save', 'submit', array(
                'attr' => array('class' => 'save'),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'BdeReventBundle\Entity\Type'));
    }

    public function getName()
    {
        return 'type';
    }
}