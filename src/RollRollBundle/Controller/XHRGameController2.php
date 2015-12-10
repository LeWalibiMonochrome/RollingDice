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

class XHRGameController2 extends UserAwareController
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
}
