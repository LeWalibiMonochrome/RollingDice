<?php

namespace RollRollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use RollRollBundle\Form\LoginType;
use RollRollBundle\Entity\Player;


class UserController extends Controller
{
    /**
     * @Route("/login")
     */
    public function indexAction()
    {

    	$player = new Player();
        $form = $this->createForm(new LoginType(), $player, array(
            'action' => '',
            'method' => 'POST',
        ));

        return $this->render('RollRollBundle:User:login.html.twig',array(
        	'Login'=> $form->createView()
        ));
    }
}
