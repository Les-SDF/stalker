<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SignInType;
use App\Form\SignUpType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends AbstractController
{
    #[Route('/users/update', name: 'update_user', methods: ['GET', 'POST'])]
    public function updateUser(): Response
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

        return $this->render('user/profile.html.twig', [
            'signInForm' => $signInForm,
            'signUpForm' => $signUpForm,
        ]);
    }

    // TODO: Vérifier que mon Expression fonctionne bien
    #[IsGranted(new Expression('not user.hasRole("ROLE_ADMIN") and (is_granted("ROLE_ADMIN") or user.getId() == subject.getId())'), 'user')]
    #[Route('/users/{id}/delete', name: 'delete_user', methods: ['GET'])]
    public function deleteUser(#[MapEntity] User      $user,
                               EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', 'User deleted successfully');

//        if ($this->getUser()->getId() === $user->getId()) {
//            return $this->redirectToRoute('app_logout');
//        }
        return $this->redirectToRoute('users_list');
    }
}