<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use App\Entity\Game;

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

        $allGames = $this->getDoctrine()->getRepository(Game::class)->findAll();

        return $this->render('game/index.html.twig', [
            'games' => $allGames 
        ]);
    }
}
