<?php

namespace App\Controller;

use App\Entity\Player;

use App\Form\ChangePasswordSettingsType;
use App\Form\DeleteAccountSettingsType;
use App\Form\ChangeEmailSettingsType;
use App\Form\ChangeUserSettingsType;
use App\Form\RegistrationFormType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Core\Security;

use Symfony\Component\HttpFoundation\Session\Session;


class PlayerController extends AbstractController
{
    /**
    * @Route("/settings", name="settings")
    */
    public function userSettings(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('welcome');
        }

        $user = new Player();
        
        $usernameForm = $this->createForm(ChangeUserSettingsType::class, $user);
        $usernameForm->handleRequest($request);

        $emailForm = $this->createForm(ChangeEmailSettingsType::class, $user);
        $emailForm->handleRequest($request);

        $passwordForm = $this->createForm(ChangePasswordSettingsType::class, $user);
        $passwordForm->handleRequest($request);

        $message = null;

        $user = $this->getUser();
        if ($usernameForm->isSubmitted() && $usernameForm->isValid()) {
            $user->setUsername(
                $usernameForm->get('username')->getData()
            );

            $message = "Username changed, you will login with it.";
        }

        if ($emailForm->isSubmitted() && $emailForm->isValid()) {
            $user->setEmail(
                $emailForm->get('email')->getData()
            );

            $message = "New e-mail direction saved.";
        }

        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $passwordForm->get('password')->getData()
                )
            );

            $message = "Password changed, don´t forget it.";
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->render('player/settings.html.twig', [
            'usernameForm' => $usernameForm->createView(),
            'emailForm' => $emailForm->createView(),
            'passwordForm' => $passwordForm->createView(),
            'message' => $message
        ]);
    }

    /**
    * @Route("/delete", name="delete_account")
    */
    public function deleteAccount()
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('welcome');
        }

        $user = $this->getUser();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        $session = $this->get('session');
        $session = new Session();
        $session->invalidate();

        return $this->redirectToRoute('welcome');
    }
}
