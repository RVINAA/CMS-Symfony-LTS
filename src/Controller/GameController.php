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
        $this->test($this);

        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
        ]);
    }

    public function test($ref) {
        if (!$ref->getUser()) {
            return $ref->redirectToRoute('welcome');
        }
    }

}
