<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SignInType;
use App\Form\SignUpType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function homepage(): Response
    {
        $user = new User();
        $signInForm = $this->createForm(SignInType::class, $user, [
            'method' => 'POST',
            'action' => $this->generateUrl('sign_in')
        ]);
        $signUpForm = $this->createForm(SignUpType::class, $user, [
            'method' => 'POST',
            'action' => $this->generateUrl('sign_up')
        ]);

        return $this->render('homepage.html.twig', [
            'signInForm' => $signInForm,
            'signUpForm' => $signUpForm,
        ]);
    }
}