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
     * @Route("/xhr/{id}/gendices/{a}/{b}/{c}", name="xhr_gendices", requirements={"id":"\d{0,10}","a":"y|n","b":"y|n","c":"y|n"})
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
        if($b == 'y') {
        	$db = rand(1,6);
        }
        if($c == 'y') {
        	$dc = rand(1,6);
        }
        $result=$da.'/'.$db.'/'.$dc;


        $grid->setLastDices($result);

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
        if($user != $grid->getOwner()) {
            return new Response("Vous ne participez pas a cette partie !");
        }
        if($user != $grid->getGame()->getCurrentPlayer()) {
            return new Response("Ce n'est pas à votre tour de jouer! ");
        }

        $couleur = intval($_POST['c']);
        $position = intval($_POST['p']);


        $strings=$grid->getLastDices();
        $tab_Dice =explode('/',$strings);

        $total=intval($tab_Dice[0])+intval($tab_Dice[1])+intval($tab_Dice[2]);

        if ($couleur == '0') {
        	if(intval($tab_Dice[0])==0){
        		return new Response('Impossible car le dé orange n est pas lancé');
        	}
        }

        if ($couleur == '1') {
        	if(intval($tab_Dice[1])==0){
        		return new Response('Impossible car le dé jaune n est pas lancé');
        	}
        }

        if ($couleur == '2') {
        	if(intval($tab_Dice[2])==0){
        		return new Response('Impossible car le dé violet n est pas lancé');
        	}
        }


		for ($i=0; $i <$position; $i++) {
    		if($grid->getCase($couleur,$i) >= $total){
    			return new Response('Il ya des valeurs plus grande avant');
    		}
		}

		$grid->setCase($couleur,$position,$total);

    	return new Response($grid->getScoreSheet());
    }
}
