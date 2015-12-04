<?php

namespace RollRollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use RollRollBundle\Entity\Game;
use RollRollBundle\Form\CreateGameType;

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
        	'games' => $this->getDoctrine()->getRepository('RollRollBundle:Grid')->findByOwner($user)
        ));
    }

    /**
     * @Route("/createGame", name="createGame")
     */
    public function createGameAction(Request $request)
    {

        $game = new Game();
        $game->setStatus(0);
        $game->setPlayerOrder(' ');

        $form = $this->createForm(new CreateGameType(), $game, array(
            'action' => '',
            'method' => 'POST',
        ));

        if ($form->handleRequest($request)->isValid()){
            $this->getDoctrine()->getManager()->persist($game);
            $this->getDoctrine()->getManager()->flush();

            return $this->render('RollRollBundle:Default:error.html.twig',array(
                'titre'=> "Partie créée",
                'message'=> "Partie ".$game->getName()." créée"
            ));
        }

        return parent::renderPage('RollRollBundle:User:createGame.html.twig',array(
            'form'=> $form->createView()
        ));
    }
}
