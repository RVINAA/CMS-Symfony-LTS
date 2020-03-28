<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class PlayerController extends AbstractController
{
    /**
     * @Route("/", name="welcome")
     */
    public function index()
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('games');
        }

        return $this->render('player/welcome.html.twig', []);
    }
}
