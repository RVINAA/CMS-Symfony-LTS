<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class PlayerController extends AbstractController
{
    /**
     * @Route("/welcome", name="welcome")
     * @Security("is_granted('IS_AUTHENTICATED_ANONYMOUSLY') and !is_granted('ROLE_USER')")
     */
    public function index()
    {
        return $this->render('player/index.html.twig', [
            'controller_name' => 'PlayerController',
        ]);
    }
}
