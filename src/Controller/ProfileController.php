<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SignInType;
use App\Form\SignUpType;
use App\Repository\UserRepository;
use App\Service\QueryBuilderServiceInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
    public function userProfile(UserRepository $repository,
                                string $code): Response
    {
        if (!$user = $repository->findByProfileCode($code)) {
            $this->addFlash('error', 'User not found');
            return $this->redirectToRoute('users_list');
        }
        if ($user->getDefaultProfileCode() === $code and $user->getCustomProfileCode()) {
            return $this->redirectToRoute('user_profile', [
                'code' => $user->getCustomProfileCode()
            ]);
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

    // TODO: Faire fonctionner cette route (ça marche pas)
    #[Route('/users/{code}/json', name: 'user_profile_json', methods: ['GET'])]
    public function userProfileJSON(UserRepository $repository,
                                    string         $code): JsonResponse
    {
        return new JsonResponse(
            json_encode($repository->findByProfileCode($code))
        );
    }
}