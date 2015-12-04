<?php

namespace RollRollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use RollRollBundle\Entity\Player;

class UserAwareController extends Controller
{
    public function getUser()
    {
    	if(!$this->get('session')->has('psd') || !$this->get('session')->has('psw')) {
			return null;
		}
		
		return $this->getDoctrine()->getRepository('RollRollBundle:Player')->findOneBy(array(
			'pseudo' => $this->get('session')->get('psd'),
			'password' => $this->get('session')->get('psw')
		));
    }

    public function saveUser(Player $user)
    {
    	$this->get('session')->set('psd',$user->getPseudo());
    	$this->get('session')->set('psw',$user->getPassword());
    }

    public function logout()
    {
        $this->get('session')->set('psd',null);
        $this->get('session')->set('psw',null);
    }

    public function renderPage($view, array $parameters = array(), Symfony\Component\HttpFoundation\Response $response = null)
    {
        $parameters = $this->addParameters($parameters);

        return parent::render($view, $parameters, $response);
    }

    public function addParameters($parameters)
    {
        $parameters['user'] = $this->getUser();

        return $parameters;
    }
}
