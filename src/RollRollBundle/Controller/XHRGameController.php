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

        if($grid->getLastDices() != 'no') {
            return new Response($grid->getLastDices());
        }

		$da = 0;
		$db = 0;
		$dc = 0;
		$des = array( false, false, false );
		if($a =='y'){
			$da=rand(1,6);
			$des[0] = true;
		}
		if($b == 'y') {
			$db = rand(1,6);
			$des[1] = true;
		}
		if($c == 'y') {
			$dc = rand(1,6);
			$des[2] = true;
		}
		$result=$da.'/'.$db.'/'.$dc;

		$val = $da + $db + $dc;

		$ok = false;
		for($clr = 0; $clr < 3; $clr++) {
			if($des[$clr]) {
				for($i = 0; $i < 9; $i++) {
					if($this->canPlace($clr, $grid, $val, $i)) {
						$ok = true;
					} else {
					}
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

		if($grid->isComplete()) {
			return new Response('fin');	
		}

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
			if($grid->getCase($col,$i) >= $valeur) {
				return false;
			}
		}

		for($i = $position+1; $i < 9; $i++) {
			if($grid->getCase($col,$i) <= $valeur && $grid->getCase($col,$i) != 0) {
				return false;
			}
		}

		return true;
	}

    /**
     * @Route("/xhr/{id}/getUsers")
     * @ParamConverter("grid", options={"id": "id"})
     *
     */
    public function gameUsersAction(Grid $grid)
    {
        $user = parent::getUser();
        $game = $grid->getGame();
        if(!$user) {
            return new Response('Vous n\'êtes plus connecté');
        }

        $grid = $this->getDoctrine()->getRepository('RollRollBundle:Grid')->findOneBy(array(
            'owner' => $user,
            'game' => $game
        ));

        if(!$grid) {
            return new Response('Grille introuvable');
        }

        $grids = $this->getDoctrine()->getRepository('RollRollBundle:Grid')->findByGame($game);
        foreach ($grids as $k => $v) {
            if($v->isComplete()) {
                return new Response('fin'); 
            }
        }

        return parent::renderPage('RollRollBundle:Default:players.txt.twig',array(
            'users' => $grids,
            'game' => $game
        ));
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
				return new Response('Il ya des valeurs plus grande avant');
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

		//
		$posi_orange=-1;
		$posi_jaune=-1;
		$posi_violet=-1;

		$colonne=0;
	
		switch($couleur){
			case 0:
				$colonne = 1 + $position ;
				if ($position >= 3) $colonne++;
				break;
			case 1:
				$colonne = $position ;
				if ($position >= 5) $colonne++;
				break;     
			case 2:
				$colonne = $position - 1;
				if ($position >= 4) $colonne++;
				break;
			default:
				break;
		}

		switch($colonne){
			case 0:
				$posi_jaune = 0;
				$posi_violet = 1;
				break;
			case 1:
			case 2:
				$posi_orange = $colonne - 1;
				$posi_jaune = $colonne;
				$posi_violet = $colonne + 1;
				break;
			case 3:
				$posi_orange = 2;
				$posi_jaune = 3;
				break;
			case 4:
				$posi_jaune = 4;
				$posi_violet = 4;
				break;
			case 5:
				$posi_orange = 3;
				$posi_violet = 5;
				break;
			case 6: case 7: case 8:
				$posi_orange = $colonne - 2;
				$posi_jaune = $colonne - 1;
				$posi_violet = $colonne + 1;
				break;
			case 9:
				$posi_orange = 7;
				$posi_jaune = 8;
				break;
			default:
				break;
		}
		if($couleur != 0 && $posi_orange != -1 && $total == $grid->getCase(0,$posi_orange)) {
			return new Response('Valeur déjà sur la colonne');
		}
		
		if($couleur != 1 && $posi_jaune != -1 && $total == $grid->getCase(1,$posi_jaune)) {
			return new Response('Valeur déjà sur la colonne');
		}
		
		if($couleur != 2 && $posi_violet != -1 && $total == $grid->getCase(2,$posi_violet)) {
			return new Response('Valeur déjà sur la colonne');
		}
		
		$this->nextPlayer($grid);

        $grid->setLastDices('no');
		$grid->setCase($couleur,$position,$total);
        
		$this->getDoctrine()->getManager()->persist($grid);
		$this->getDoctrine()->getManager()->flush();

		return new Response('ok');
	}
}

















