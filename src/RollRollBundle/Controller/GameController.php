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
     * @Route("/reset", name="reset")
     */
    public function resetAction()
    {
        $g = $this->getDoctrine()->getRepository('RollRollBundle:Grid')->findAll();
        $o = $this->getDoctrine()->getRepository('RollRollBundle:Game')->findAll();
        $m = $this->getDoctrine()->getManager();
        foreach ($g as $key => $value) {
            $m->remove($value);
        }
        foreach ($o as $key => $value) {
            $m->remove($value);
        }
        $m->flush();

        return $this->gamesAction();
    }

    /**
     * @Route("/games", name="games")
     */
    public function gamesAction()
    {
        $user = parent::getUser();

    	if(!$user) {
    		return parent::renderPage('RollRollBundle:Default:error.html.twig',array(
        		'titre'=> "Utilisateur inconnu",
        		'message'=> "Vous devez être connecté pour accéder à cette page"
        	));
    	}

        $users = array();
        $games = $this->getDoctrine()->getRepository('RollRollBundle:Game')->findAll();
        foreach ($games as $k => $v) {
            $x = $this->getDoctrine()->getRepository('RollRollBundle:Grid')->findByGame($v);
            $a = array();

            foreach ($x as $k2 => $v2) {
                $a[] = $v2->getOwner();
            }

            $users[$v->getId()] = $a;
        }

        return parent::renderPage('RollRollBundle:User:games.html.twig',array(
        	'games' => $games,
            'users' => $users,
            'user' => $user
        ));
    }

    /**
     * @Route("/game/{id}", name="game")
     * @ParamConverter("game", options={"id": "id"})
     *
     */
    public function gameAction(Game $game)
    {
        $user = parent::getUser();
        if(!$user) {
            return $this->render('RollRollBundle:Default:error.html.twig',array(
                'titre'=> "Utilisateur inconnu",
                'message'=> "Vous devez être connecté pour accéder à cette page"
            ));
        }

        $grid = $this->getDoctrine()->getRepository('RollRollBundle:Grid')->findOneBy(array(
            'owner' => $user,
            'game' => $game
        ));
        if(!$grid) {
            return $this->render('RollRollBundle:Default:error.html.twig',array(
                'titre'=> "Utilisateur inconnu",
                'message'=> "Vous ne participez pas a cette partie !"
            ));
        }

        return parent::renderPage('RollRollBundle:Default:plateau.html.twig',array(
            'game' => $game,
            'grid' => $grid,
            'users' => $this->getDoctrine()->getRepository('RollRollBundle:Grid')->findByGame($game)
        ));
    }

    /**
     * @Route("/joinGame/{id}", name="joinGame")
     * @ParamConverter("game", options={"id": "id"})
     */
    public function joinGameAction(Game $game)
    {
        $user = parent::getUser();

        if(!$user) {
            return $this->render('RollRollBundle:Default:error.html.twig',array(
                'titre'=> "Utilisateur inconnu",
                'message'=> "Vous devez être connecté pour accéder à cette page"
            ));
        }

        $egrid = $this->getDoctrine()->getRepository('RollRollBundle:Grid')->findOneBy(array(
            'game' => $game,
            'owner' => $user
        ));

        if($egrid) {
            return $this->render('RollRollBundle:Default:error.html.twig',array(
                'titre'=> "Echec",
                'message'=> "Vous participez déjà a cette partie"
            ));
        }

        $pls = $this->getDoctrine()->getRepository('RollRollBundle:Grid')->findNbPlayers($game);
        
        if($pls >= $game->getNbPlayers()) {
            return $this->render('RollRollBundle:Default:error.html.twig',array(
                'titre'=> "Echec",
                'message'=> "Cette partie est déjà pleine !"
            ));
        }        

        $grid = new Grid();
        $grid->setScoreSheet('..');
        $grid->setPlayed(0);
        $grid->setOwner($user);
        $grid->setGame($game);
        $grid->setPlayerOrder($this->getDoctrine()->getRepository('RollRollBundle:Grid')->getMax($game)+1);

        $this->getDoctrine()->getManager()->persist($grid);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateUrl('games'));
    }

    /**
     * @Route("/createGame", name="createGame")
     */
    public function createGameAction(Request $request)
    {
        $user = parent::getUser();

        if(!$user) {
            return parent::renderPage('RollRollBundle:Default:error.html.twig',array(
                'titre'=> "Utilisateur inconnu",
                'message'=> "Vous devez être connecté pour accéder à cette page"
            ));
        }

        $game = new Game();
        $game->setStatus(0);
        $game->setCurrentPlayer($user);

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

            return parent::renderPage('RollRollBundle:Default:error.html.twig',array(
                'titre'=> "Partie créée",
                'message'=> "Partie ".$game->getName()." créée"
            ));
        }

        return parent::renderPage('RollRollBundle:User:createGame.html.twig',array(
            'form'=> $form->createView()
        ));
    }
}
