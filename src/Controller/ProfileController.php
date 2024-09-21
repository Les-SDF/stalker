<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SignInType;
use App\Form\SignUpType;
use App\Repository\UserRepository;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route('/users', name: 'users_list')]
    public function usersList(UserRepository     $repository,
                              PaginatorInterface $paginator,
                              Request            $request): Response
    {
        $queryBuilder = $this->createQueryBuilder('u', null, $repository);

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

    // TODO: Ca ne devrait pas être un service ?
    public function createQueryBuilder(string         $alias,
                                       ?string        $indexBy = null,
                                       UserRepository $repository): QueryBuilder
    {
        $queryBuilder = $repository->createQueryBuilder($alias);
        if (!$this->isGranted("ROLE_ADMIN")) {
            $queryBuilder->andWhere('u.visibility = :visibility')
                ->setParameter('visibility', 'public');
        }
        return $queryBuilder;
    }

    #[Route('/users/{id}', name: 'user_profile', methods: ['GET'])]
    public function userProfile(UserRepository $repository,
                                User           $user): Response
    {
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
}