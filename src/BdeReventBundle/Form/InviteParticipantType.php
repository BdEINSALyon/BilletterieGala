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
class InviteParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', 'text', array(
                'attr' => array('placeholder' => 'Prénom'),
                'label' => false,
                'required' => true
            ))
            ->add('lastname', 'text', array(
                'attr' => array('placeholder' => 'Nom'),
                'label' => false,
                'required' => true
            ))
            ->add('email', 'email', array(
                'attr' => array('placeholder' => 'EMail'),
                'label' => false,
                'required' => true
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }

    public function getName()
    {
        return 'invite_participant';
    }
}