<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SignInType;
use App\Form\SignUpType;
use App\Form\UpdateType;
use App\Form\UpdateCodeType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ProfileController extends AbstractController
{
    #[Route('/users/{profileCode}', name: 'user_profile', methods: ['GET', 'POST'])]
    public function userProfile(string                         $profileCode,
                                Request                        $request,
                                EntityManagerInterface         $entityManager,
                                UserRepository                 $repository): Response
    {
        if (!$user = $repository->findByProfileCode($profileCode)) {
            $this->addFlash('error', 'User not found');
            return $this->redirectToRoute('homepage');
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
        $updateCodeForm = $this->createForm(UpdateCodeType::class, $newUser, [
            'method' => 'POST',
            'action' => $this->generateUrl('user_profile', ['profileCode' => $user->getProfileCode()])
        ]);
        $updateForm = $this->createForm(UpdateType::class, $newUser, [
            'method' => 'POST',
            'action' => $this->generateUrl('update_user', [
                'profileCode' => $user->getProfileCode()
            ]),
        ]);

        return $this->render('user/user-profile.html.twig', [
            'signInForm' => $signInForm,
            'signUpForm' => $signUpForm,
            'updateCodeForm' => $updateCodeForm,
            'updateForm' => $updateForm,
            'user' => $user
        ]);
    }

    #[Route('/users/{profileCode}/json', name: 'user_profile_json', options: ["expose" => true], methods: ['GET'])]
    public function userProfileJSON(string              $profileCode,
                                    UserRepository      $repository,
                                    SerializerInterface $serializer): JsonResponse
    {
        if (!$user = $repository->findByProfileCode($profileCode)) {
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