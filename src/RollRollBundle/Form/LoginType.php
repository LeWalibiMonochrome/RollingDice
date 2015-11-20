<?php
// src/RollRollBundle/Form/LoginType.php

namespace RollRollBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LoginType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('pseudo',      'text')
      ->add('password',     'password')
      ->add('save',      'submit')
    ;
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'RollRollBundle\Entity\Player'
    ));
  }

  public function getName()
  {
    return 'LoginType';
  }
}