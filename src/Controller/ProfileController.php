<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\CountryServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ProfileController extends AbstractController
{
    #[Route('/users/{profileCode}', name: 'user_profile', methods: ['GET', 'POST'])]
    public function userProfile(string                  $profileCode,
                                UserRepository          $repository,
                                CountryServiceInterface $countryService): Response
    {
        if (!$user = $repository->findByProfileCode($profileCode)) {
            $this->addFlash('error', 'User not found');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/user-profile.html.twig', [
            'country' => $countryService->getCountryName($user->getCountryCode()),
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

        $data = $serializer->serialize(
            $user,
            'json',
            ['groups' => ['user_public']]
        );

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }
}