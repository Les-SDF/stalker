<?php

namespace App\Controller;

use App\Enum\Visibility;
use App\Form\SignUpType;
use App\Service\FlashMessageHelperInterface;
use App\Service\FormManagerInterface;
use App\Service\UserManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthenticationController extends AbstractController
{
    #[Route('/sign-up', name: 'sign_up', methods: ['POST', 'GET'])]
    public function signUp(FormManagerInterface        $formManager,
                           Request                     $request,
                           UserManagerInterface        $userManager,
                           EntityManagerInterface      $entityManager,
                           FlashMessageHelperInterface $flashMessageHelper): Response
    {
        if ($this->isGranted('ROLE_USER')) {
            $this->addFlash("warning", "You are already signed in.");
            return $this->redirectToRoute('homepage');
        }

        $form = $formManager->createForm(
            type: SignUpType::class,
            route: 'sign_up'
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $userManager->hashPassword(
                user: $user,
                password: $form->get('password')->getData()
            );
            $user->setVisibility(
                visibility: $form->get('visibility')->getData()
                    ? Visibility::Private
                    : Visibility::Public
            );
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'You are signed up.');
            return $this->redirectToRoute(
                $request->attributes->get('_route')
            );
        } else {
            $flashMessageHelper->addFormErrorsAsFlashMessages($form);
        }

        // TODO: Faire en sorte que le formulaire soit reaffiché automatiquement en cas d'erreur
        return $this->redirectToRoute('homepage');
    }

    // TODO: Vérifier si la date de connexion est bien mise à jour
    #[Route('/sign-in', name: 'sign_in', methods: ['GET', 'POST'])]
    public function signIn(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->isGranted("ROLE_USER")) {
            $this->addFlash("warning", "You are already signed in.");
            return $this->redirectToRoute("homepage");
        }
        return $this->redirectToRoute("homepage", [
            "email" => $authenticationUtils->getLastUsername(),
        ]);
    }
}