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

        $val = $da + $db + $dc;

        $ok = false;
        for($clr = 0; $clr < 3; $clr++) {
            for($i = 0; $i < 9; $i++) {
                if($this->canPlace($clr, $grid, $val, $i)) {
                    $ok = true;
                }
            }
        }

        if(!$ok) {
            $grid->miss();
            $this->getDoctrine()->getManager()->persist($grid);
            $this->getDoctrine()->getManager()->flush();

            $this->nextPlayer($grid);
            return new Response('x');
        }

        $grid->setLastDices($result);

        $this->getDoctrine()->getManager()->persist($grid);
        $this->getDoctrine()->getManager()->flush();

        return new Response($result);
    }

    private function nextPlayer(Grid $grid)
    {
        $nb = $grid->getPlayerOrder()+1;

        if($grid->getPlayerOrder() == $grid->getGame()->getNbPlayers()) {
            $nb = 1;
        }

        $nextGrid = $this->getDoctrine()->getRepository('RollRollBundle:Grid')->findOneBy(array(
            'game' => $grid->getGame(),
            'playerOrder' => $nb
        ));

        if($nextGrid) {
            $g = $grid->getGame();
            $g->setCurrentPlayer($nextGrid->getOwner());
            $this->getDoctrine()->getManager()->persist($g);
            $this->getDoctrine()->getManager()->flush();
        }
    }

    private function canPlace($col, Grid $grid, $valeur, $position)
    {
        if($grid->getCase($col,$position) != 0) {
            return false;
        }

        for($i = 0; $i < $position; $i++) {
            if($grid->getCase($col,$i) > $valeur) {
                return false;
            }
        }

        for($i = $position+1; $i < 9; $i++) {
            if($grid->getCase($col,$i) < $valeur) {
                return false;
            }
        }

        return true;
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

        $couleur = intval($_POST['couleur']);
        $position = intval($_POST['position']);

        $tab_Dice = explode('/', $grid->getLastDices());
        $total = intval($tab_Dice[0])+intval($tab_Dice[1])+intval($tab_Dice[2]);

        if($couleur < 0 || $couleur > 2 || $position < 0 || $position > 9) {
            return new Response("Mauvaises valeur reçues");
        }

        if($couleur == 0 && intval($tab_Dice[0]) == 0) {
       		return new Response('Impossible car le dé orange n est pas lancé');
        }

        if ($couleur == 1 && intval($tab_Dice[1]) == 0) {
        	return new Response('Impossible car le dé jaune n est pas lancé');
        }

        if ($couleur == 2 && intval($tab_Dice[2]) == 0) {
        	return new Response('Impossible car le dé violet n est pas lancé');
        }


		for($i=0; $i <$position; $i++) {
    		if($grid->getCase($couleur,$i) >= $total){
    			return new Response('Il ya des valeurs plus grande avant : '.$i.' '.$grid->getCase($couleur,$i) );
    		}
		}

        for($i=$position+1; $i < 9; $i++) {
            if($grid->getCase($couleur,$i) != 0 && $grid->getCase($couleur,$i) <= $total){
                return new Response('Il ya des valeurs plus petites après');
            }
        }

        if($grid->getCase($couleur,$position) != 0) {
            return new Response('Cette case est déjà remplie');
        }

        $posi_orange=0;
        $posi_jaune=0;
        $posi_violet=0;

        $colonne=0;

        /**
        if($couleur==0){
        	$colonne=$position+2;
        	if($colonne==7){
        		$posi_jaune
        	}
        	$grid->getCase(1,$colonne)
        }
        if($couleur==1){
        	$colonne=$position+1;
        }
        if($couleur==2){
        	$colonne=$position;
        }
        */

        $this->nextPlayer($grid);

		$grid->setCase($couleur,$position,$total);
        $this->getDoctrine()->getManager()->persist($grid);
        $this->getDoctrine()->getManager()->flush();

    	return new Response('ok');
    }
}
