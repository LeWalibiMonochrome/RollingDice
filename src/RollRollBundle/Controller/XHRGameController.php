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
     * @Route("/xhr/gendices/{a}/{b}/{c}", name="xhr_gendices", requirements={"a":"y|n","b":"y|n","c":"y|n"})
     */
    public function gendicesAction()
    {
    	return new Response(rand(1,6).'/'.rand(1,6).'/'.rand(1,6));
    }


       /**
     * @Route("/xhr/placeDices")
     */
    public function placeDiceAction()
    {
    	return new Response('ok');
    }
}
