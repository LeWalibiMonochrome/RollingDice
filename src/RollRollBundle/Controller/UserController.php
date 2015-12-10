<?php

namespace RollRollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use RollRollBundle\Form\LoginType;
use RollRollBundle\Entity\Player;


class UserController extends UserAwareController
{
    /**
     * @Route("/register",name="register")
     */
    public function registerAction(Request $request)
    {

    	$player = new Player();
        $form = $this->createForm(new LoginType(), $player, array(
            'action' => '',
            'method' => 'POST',
        ));

        if ($form->handleRequest($request)->isValid()){

       	 	 $this->getDoctrine()->getManager()->persist($player);
       		$this->getDoctrine()->getManager()->flush();
       		return $this->render('RollRollBundle:Default:error.html.twig',array(
            	'titre'=> "Inscription Ok",
            	'message'=> "ID : " .$player->getId()
            ));


        }

        return parent::renderPage('RollRollBundle:User:login.html.twig',array(
        	'Login'=> $form->createView(),
            'title'=> 'Inscription'
        ));

    }

    /**
     * @Route("/liste")
     */
    public function listeUsersAction()
    {
        $repo = $this->getDoctrine()->getRepository("RollRollBundle:Player");

        return parent::renderPage('RollRollBundle:Test:users.html.twig',array(
            'users' => $repo->findAll()
        ));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        parent::logout();
        return $this->redirect($this->generateUrl('home'));
    }

    /**
     * @Route("/login",name="login")
     */
    public function loginAction(Request $request)
    {

    	$player = new Player();
        $form = $this->createForm(new LoginType(), $player, array(
            'action' => '',
            'method' => 'POST',
        ));

        if ($form->handleRequest($request)->isValid()){

         	$repo = $this->getDoctrine()->getRepository("RollRollBundle:Player");
         	$user = $repo->findOneBy(array(
                'pseudo' => $player->getPseudo(),
                'password' => $player->getPassword()
            ));

         	if($user){
                parent::saveUser($player);
             	return $this->redirect($this->generateUrl('games'));
         	} else {
             	return $this->render('RollRollBundle:Default:error.html.twig',array(
            		'titre'=> "Erreur Login",
            		'message'=> "Erreur"
            	));
         	}
        }

        return parent::renderPage('RollRollBundle:User:login.html.twig',array(
        	'Login'=> $form->createView(),
            'title'=>'Connexion'
        ));
    }

}
