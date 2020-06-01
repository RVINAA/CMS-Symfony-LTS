<?php

namespace App\Controller;

use App\Entity\Player;
use App\Form\RegistrationFormType;
use App\Security\LoginAuthAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     * @Security("is_granted('IS_AUTHENTICATED_ANONYMOUSLY') and !is_granted('ROLE_USER')")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginAuthAuthenticator $authenticator): Response
    {
        $user = new Player();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUsername(
                $form->get('username')->getData()
            );

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $user->setEmail(
                $form->get('email')->getData()
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
