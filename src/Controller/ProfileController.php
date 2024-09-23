<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SignInType;
use App\Form\SignUpType;
use App\Repository\UserRepository;
use App\Service\ProfileCodeRedirectorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ProfileController extends AbstractController
{
    #[Route('/users/{code}', name: 'user_profile', methods: ['GET'])]
    public function userProfile(string                         $code,
                                UserRepository                 $repository,
                                ProfileCodeRedirectorInterface $profileCodeRedirector): Response
    {
        if (!$user = $repository->findByProfileCode($code)) {
            $this->addFlash('error', 'User not found');
            return $this->redirectToRoute('homepage');
        }
        if ($profileCodeRedirector->isRedirectableWithCustomProfileCode($user, $code)) {
            return $profileCodeRedirector->redirectToRouteWithCustomProfileCode('user_profile');
        }
        $newUser = new User();
        $signInForm = $this->createForm(SignInType::class, $newUser, [
            'method' => 'POST',
            'action' => $this->generateUrl('sign_in')
        ]);
        $signUpForm = $this->createForm(SignUpType::class, $newUser, [
            'method' => 'POST',
            'action' => $this->generateUrl('sign_up')
        ]);

        return $this->render('user/user-profile.html.twig', [
            'signInForm' => $signInForm,
            'signUpForm' => $signUpForm,
            'user' => $user
        ]);
    }

    #[Route('/users/{code}/json', name: 'user_profile_json', options: ["expose" => true], methods: ['GET'])]
    public function userProfileJSON(string              $code,
                                    UserRepository      $repository,
                                    SerializerInterface $serializer): JsonResponse
    {
        if (!$user = $repository->findByProfileCode($code)) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(
            $serializer->serialize($user, 'json'),
            Response::HTTP_OK,
            [],
            true
        );
    }
}