<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SignUpType;
use App\Service\FlashMessageHelperInterface;
use App\Service\UserManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/sign-up', name: 'signUp', methods: ['GET', 'POST'])]
    public function signUp(Request                     $request,
                           EntityManagerInterface      $entityManager,
                           UserManagerInterface        $userManager,
                           FlashMessageHelperInterface $flashMessageHelper): Response
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('feed');
        }
        $user = new User();
        $form = $this->createForm(SignUpType::class, $user, [
            'method' => 'POST',
            'action' => $this->generateURL('signUp')
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $userManager->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Vous êtes inscrit');
            return $this->redirectToRoute('feed');
        } else {
            $flashMessageHelper->addFormErrorsAsFlashMessages($form);
        }
        return $this->render('user/sign-up.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/sign-in', name: 'signIn')]
    public function signIn(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}