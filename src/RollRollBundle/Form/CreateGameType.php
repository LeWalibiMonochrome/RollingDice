<?php
// src/RollRollBundle/Form/LoginType.php

namespace RollRollBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CreateGameType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('nbPlayers',      'number')
      ->add('name',     'text')
      ->add('create',      'submit')
    ;
  }

}