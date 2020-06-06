<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class PlayerController extends AbstractController
{    
    /**
    * @Route("/settings", name="settings")
    */
    public function userSettings() 
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('welcome');
        }

        
    }
}
