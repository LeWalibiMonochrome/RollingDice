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
      ->add('nbPlayers','number',array(
      		'label' => 'Nombre de joueurs'
      ))
      ->add('name','text',array(
      		'label' => 'Nom de la partie'
      ))
      ->add('create','submit',array(
      		'label' => 'Créer'
      ))
    ;
  }

}