<?php

namespace RollRollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use RollRollBundle\Entity\Game;
use RollRollBundle\Entity\Grid;
use RollRollBundle\Form\CreateGameType;
use Symfony\Component\HttpFoundation\Request;

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

        $users = array();
        $games = $this->getDoctrine()->getRepository('RollRollBundle:Grid')->findByOwner($user);
        foreach ($variable as $key => $value) {
            # code...
        }

        return parent::renderPage('RollRollBundle:User:games.html.twig',array(
        	'games' => $games
        ));
    }

    /**
     * @Route("/game/{id}", name="game")
     * @ParamConverter("game", options={"id": "id"})
     *
     */
    public function gameAction(Game $game)
    {
        return parent::renderPage('RollRollBundle:Default:plateau.html.twig',array(
            'game' => $game
        ));
    }

    /**
     * @Route("/createGame", name="createGame")
     */
    public function createGameAction(Request $request)
    {
        $user = parent::getUser();

        if(!$user) {
            return $this->render('RollRollBundle:Default:error.html.twig',array(
                'titre'=> "Utilisateur inconnu",
                'message'=> "Vous devez être connecté pour accéder à cette page"
            ));
        }

        $game = new Game();
        $game->setStatus(0);
        $game->setPlayerOrder($user->getId().'');

        $grid = new Grid();
        $grid->setScoreSheet('..');
        $grid->setPlayed(0);
        $grid->setOwner($user);
        $grid->setGame($game);
        $grid->setPlayerOrder(1);
        
        $form = $this->createForm(new CreateGameType(), $game, array(
            'action' => '',
            'method' => 'POST',
        ));

        if ($form->handleRequest($request)->isValid()){
            $this->getDoctrine()->getManager()->persist($game);
            $this->getDoctrine()->getManager()->persist($grid);
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
