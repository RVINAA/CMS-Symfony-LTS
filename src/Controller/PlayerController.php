<?php

namespace App\Controller;

use App\Entity\Player;
use App\Form\ChangeEmailSettingsType;
use App\Form\ChangePasswordSettingsType;
use App\Form\ChangeUserSettingsType;
use App\Form\DeleteAccountSettingsType;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PlayerController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    /**
    * @Route("/settings", name="settings")
    */
    public function userSettings(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();
        $usernameForm = $this->createForm(ChangeUserSettingsType::class, $user);
        $usernameForm->handleRequest($request);

        $emailForm = $this->createForm(ChangeEmailSettingsType::class, $user);
        $emailForm->handleRequest($request);

        $passwordForm = $this->createForm(ChangePasswordSettingsType::class, $user);
        $passwordForm->handleRequest($request);

        /*$deleteForm = $this->createForm(DeleteAccountSettingsType::class, $user);
        $deleteForm->handleRequest($request);*/

        if ($usernameForm->isSubmitted() && $usernameForm->isValid()) {
            $user->setUsername(
                $usernameForm->get('username')->getData()
            );
        }

        if ($emailForm->isSubmitted() && $emailForm->isValid()) {
            $user->setEmail(
                $emailForm->get('email')->getData()
            );
        }

        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $passwordForm->get('plainPassword')->getData()
                )
            );
        }
        /*
        if ($deleteForm->isSubmitted() && $form4->isValid()) {
            $user->setUsername(
                $deleteForm->get('username')->getData()
            );
        }*/

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->render('settings/settings.html.twig', [
            'username' => $usernameForm->createView(),
            'email' => $emailForm->createView(),
            'password' => $passwordForm->createView(),
            //'delete' => $deleteForm->createView(),
        ]);
    }
}
