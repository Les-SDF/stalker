<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SignInType;
use App\Form\SignUpType;
use App\Repository\UserRepository;
use App\Service\ProfileCodeRedirectorInterface;
use App\Service\QueryBuilderServiceInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ProfileController extends AbstractController
{
    #[Route('/users', name: 'users_list')]
    public function usersList(PaginatorInterface           $paginator,
                              QueryBuilderServiceInterface $queryBuilderService,
                              Request                      $request): Response
    {
        $queryBuilder = $queryBuilderService->createQueryBuilder('u');

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        $userNew = new User();
        $signInForm = $this->createForm(SignInType::class, $userNew, [
            'method' => 'POST',
            'action' => $this->generateUrl('sign_in')
        ]);
        $signUpForm = $this->createForm(SignUpType::class, $userNew, [
            'method' => 'POST',
            'action' => $this->generateUrl('sign_up')
        ]);

        return $this->render('user/users-list.html.twig', [
            'pagination' => $pagination,
            'signInForm' => $signInForm,
            'signUpForm' => $signUpForm,
        ]);
    }

    #[Route('/users/{code}', name: 'user_profile', methods: ['GET'])]
    public function userProfile(string                         $code,
                                UserRepository                 $repository,
                                ProfileCodeRedirectorInterface $profileCodeRedirector): Response
    {
        if (!$user = $repository->findByProfileCode($code)) {
            $this->addFlash('error', 'User not found');
            return $this->redirectToRoute('users_list');
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