<?php

namespace RollRollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class GameController extends UserAwareController
{
    /**
     * @Route("/games", name="games")
     */
    public function gamesAction()
    {
    	$user = parent::getUser();

    	if(!$user) {
    		return $this->render('RollRollBundle:Default:error.html.twig',array(
        		'titre'=> "Utilisateur inconnu",
        		'message'=> "Vous devez être connecté pour accéder à cette page"
        	));
    	}

        return parent::renderPage('RollRollBundle:User:games.html.twig',array(
        	'games' => $this->getDoctrine()->getRepository('RollRollBundle:Game')->findByOwner($user)
        ));
    }
}
