<?php

namespace RollRollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends UserAwareController
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        return parent::renderPage('RollRollBundle:Default:plateau.html.twig');
    }
}
