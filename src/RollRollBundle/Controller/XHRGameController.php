<?php

namespace RollRollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use RollRollBundle\Entity\Game;
use RollRollBundle\Entity\Grid;
use RollRollBundle\Form\CreateGameType;

class XHRGameController extends UserAwareController
{
    /**
     * @Route("/xhr/gendices/{id}/{a}/{b}/{c}", name="xhr_gendices", requirements={"a":"y|n","b":"y|n","c":"y|n"})
     * @ParamConverter("grid", options={"id": "id"})
     */



    public function gendicesAction($a,$b,$c,Grid $grid)
    {


        $user = parent::getUser();
        if(!$user) {
            return new Response("Vous devez être connecté !");
        }
        if($user != $grid->getOwner()) {
            return new Response("Vous ne participez pas a cette partie !");
        }

        if($user != $grid->getGame()->getCurrentPlayer()) {
            return new Response("Ce n'est pas à votre tour de jouer! ");
        }


        $da=0;
        $db=0;
        $dc=0;

        if($a =='y'){
        	$da=rand(1,6);
        }
        if($b =='y'){
        	$db=rand(1,6);
        }
        if($c =='y'){
        	$dc=rand(1,6);
        }
        $result=$da.'/'.$db.'/'.$dc;


        $grid->setLastDice($result);

         $this->getDoctrine()->getManager()->persist($grid);
       		$this->getDoctrine()->getManager()->flush();

    	return new Response($result);
    }


    /**
     * @Route("/xhr/{id}/placeDices")
     * @ParamConverter("game", options={"id": "id"})
     */
    public function placeDiceAction(Grid $grid)
    {
        $user = parent::getUser();
        if(!$user) {
            return new Response("Vous devez être connecté !");
        }
        if($user != $grid) {
            return new Response("Vous ne participez pas a cette partie !");
        }

    	return new Response('ok');
    }
}
