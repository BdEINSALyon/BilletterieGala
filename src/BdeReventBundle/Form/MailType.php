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

class MailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('type')
        ->add('object')
            ->add('message', null, array('required' => false, 'attr' => array('rows' => 30)))
            ->add('save', 'submit', array(
                'attr' => array('class' => 'save'),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'BdeReventBundle\Entity\Mail'));
    }

    public function getName()
    {
        return 'type';
    }
}