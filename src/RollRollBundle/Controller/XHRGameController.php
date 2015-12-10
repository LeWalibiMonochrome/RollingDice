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
     * @Route("/xhr/{id}/gendices/{a}/{b}/{c}", name="xhr_gendices", requirements={"a":"y|n","b":"y|n","c":"y|n"})
     * @ParamConverter("grid", options={"id": "id"})
     */
    public function gendicesAction(Grid $grid)
    {
        $user = parent::getUser();
        if(!$user) {
            return new Response("Vous devez être connecté !");
        }
        if($user != $grid) {
            return new Response("Vous ne participez pas a cette partie !");
        }

    	return new Response(rand(1,6).'/'.rand(1,6).'/'.rand(1,6));
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
