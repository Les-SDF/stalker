<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\Visibility;
use App\Form\SignUpType;
use App\Service\FlashMessageHelperInterface;
use App\Service\UserManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthenticationController extends AbstractController
{
    #[Route('/sign-up', name: 'sign_up', methods: ['POST'])]
    public function signUp(Request                     $request,
                           EntityManagerInterface      $entityManager,
                           UserManagerInterface        $userManager,
                           FlashMessageHelperInterface $flashMessageHelper): Response
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('homepage');
        }
        $user = new User();
        $form = $this->createForm(SignUpType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
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
            $this->addFlash('success', 'Vous êtes inscrit');
//        $currentRoute = $request->attributes->get('_route');
            return $this->redirectToRoute('homepage');
        } else {
            $flashMessageHelper->addFormErrorsAsFlashMessages($form);
            $showSignUpModal = true;
        }
        // on doit récupérer la route depuis laquelle cette méthode a été appelée,
        // pour y rediriger l'utilisateur après l'inscription,
        // et en cas d'erreur, faire afficher le formulaire automatiquement
//        $currentRoute = $request->attributes->get('_route');
        return $this->redirectToRoute('homepage', [
            'form' => $form,
            //'showSignUpModal' => $showSignUpModal ?? false,
        ]);
    }

    #[Route('/sign-in', name: 'sign_in', methods: ['GET', 'POST'])]
    public function signIn(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->isGranted("ROLE_USER")) {
            return $this->redirectToRoute("homepage");
        }
        // on doit récupérer la route depuis laquelle cette méthode a été appelée,
        // pour y rediriger l'utilisateur après l'inscription,
        // et en cas d'erreur, faire afficher le formulaire automatiquement
        return $this->redirectToRoute("homepage", [
            "email" => $authenticationUtils->getLastUsername(),
        ]);
    }
}