<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\Score;
use App\Entity\Game;

class ScoreController extends AbstractController
{
    /**
     * @Route("/score/add", name="score_petition")
     */
    public function addScore()
    {
        if (!$this->getUser())
            return $this->redirectToRoute('welcome');

        $requestScore = (isset($_POST['Score'])) ? $_POST['Score'] : null;
        $requestGame = (isset($_POST['Game'])) ? $_POST['Game'] : null;

        if (is_null($requestScore) || is_null($requestGame))
            throw new BadRequestHttpException();

        ///////////////////////////////////////////////////////////

        $entityManager = $this->getDoctrine()->getManager();
        $game = $entityManager->getRepository(Game::class)->findOneBy(['name' => $requestGame]);

        $score = new Score();
        $score->setGame($game);
        $score->setPlayer($this->getUser());
        $score->setScore($requestScore);

        $entityManager->persist($score);
        $entityManager->flush();

        ///////////////////////////////////////////////////////////

        $response = new JsonResponse(
            ['route' => $this->generateUrl('scores', ['slug' => $game->getSlug()])]
        );

        return $response;
    }

    /**
     * @Route("/score/{slug}", name="scores")
     */
    public function index($slug)
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

        $allScores = $this->getDoctrine()->getRepository(Score::class)->findBy(["game" => $oneGame], ['score' => 'DESC'], 20);

        return $this->render('score/index.html.twig', [
            'allScores' => $allScores,
            'slug' => $oneGame->getSlug()
        ]);
    }
}