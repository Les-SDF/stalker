<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SignInType;
use App\Form\SignUpType;
use App\Repository\UserRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    #[Route('/user', name: 'displayPublicUser')]
    public function index(UserRepository $repository, PaginatorInterface $paginator,Request $request): Response
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

        return $this->render('user/displayUser.html.twig', [
            'pagination' => $pagination,
            'signInForm' => $signInForm,
            'signUpForm' => $signUpForm,
        ]);
    }

    public function createQueryBuilder(string $alias, ?string $indexBy = null, UserRepository $repository): QueryBuilder
    {
        $queryBuilder = $repository->createQueryBuilder($alias);
        if (!$this->isGranted("ROLE_ADMIN")) {
            $queryBuilder->andWhere('u.visibility = :visibility')
                ->setParameter('visibility', 'public');
        }
        return $queryBuilder;
    }

    #[Route('/user/update', name: 'updateUser', methods: ['GET', 'POST'])]
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

        return $this->render('user/modals/profile.html.twig', [
            'signInForm' => $signInForm,
            'signUpForm' => $signUpForm,
        ]);
    }
}
