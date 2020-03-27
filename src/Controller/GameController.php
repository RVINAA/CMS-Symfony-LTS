<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class GameController extends AbstractController
{
    /**
     * @Route("/games", name="games")
     */
    public function index()
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('welcome');
        }

        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
        ]);
    }
}
