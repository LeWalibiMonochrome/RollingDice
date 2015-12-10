<?php

namespace RollRollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
    public function gendicesAction(Grid $grid)
    {
    	return new Response(rand(1,6).'/'.rand(1,6).'/'.rand(1,6));
    }


    /**
     * @Route("/xhr/{id}/placeDices")
     * @ParamConverter("game", options={"id": "id"})
     */
    public function placeDiceAction(Grid $grid)
    {
    	return new Response('ok');
    }
}
