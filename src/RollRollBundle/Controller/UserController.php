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

         if ($form->handleRequest($request)->isValid()){

         	$repo = $this->getDoctrine()->getRepository("RollRollBundle:Player");
         	$user = $repo->findOneByPseudo($player->getPseudo());
         	if($user){
         		 return $this->render('RollRollBundle:Default:error.html.twig',array( //Login réussi
        		'titre'=> "Login OK",
        		'message'=> "Ok"
        	));



         	}else{
         	return $this->render('RollRollBundle:Default:error.html.twig',array(
        		'titre'=> "Erreur Login",
        		'message'=> "Erreur"
        	));
         	}



         }

        return $this->render('RollRollBundle:User:login.html.twig',array(
        	'Login'=> $form->createView()
        ));


    }
}
