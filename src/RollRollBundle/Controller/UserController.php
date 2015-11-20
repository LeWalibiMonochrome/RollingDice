<?php

namespace RollRollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserController extends Controller
{
    /**
     * @Route("/login")
     */
    public function indexAction()
    {
        return $this->render('RollRollBundle:User:login.html.twig');
    }
}
