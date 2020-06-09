<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use App\Entity\Game;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        return $this->render('game/lobbygames.html.twig', [
            'games' => $allGames 
        ]);
    }

    /**
     * @Route("/games/{slug}", name="onegame")
     */
    public function game($slug)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('welcome');
        }

        $allGames = $this->getDoctrine()->getRepository(Game::class)->findAll();
        $oneGame = null;

        foreach ($allGames as $game) {
            if ($game->getSlug() == $slug) {
                $oneGame = $game;
            }
        }

        if (is_null($oneGame)) {
            throw new NotFoundHttpException();
        }

        return $this->render('game/game.html.twig', [
            'game' => $oneGame,
            'cssFile' => "games/".$oneGame->getSlug()."/css/style.css",
            'indexFile' => '@games/'.$oneGame->getSlug().'/index.html'
        ]);
    }

    /**
     * @Route("/resolutionnotsupported", name="device_resolution_not_suported")
     */
    public function deviceResolutionNotSupported()
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('welcome');
        }

        return $this->render('game/errorSizeGame.html.twig', []);
    }


}
