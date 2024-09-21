<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SignInType;
use App\Form\SignUpType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/user/{id}/delete', name: 'deleteUser', methods: ['GET'])]
    public function deleteUser(#[MapEntity] User $user, EntityManagerInterface $entityManager): Response
    {
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer un utilisateur avec le rôle ADMIN.');
            return $this->redirectToRoute('displayPublicUser');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', 'Utilisateur supprimé avec succès.');
        return $this->redirectToRoute('displayPublicUser');
    }

}
