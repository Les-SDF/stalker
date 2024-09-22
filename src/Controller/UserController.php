<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SignInType;
use App\Form\SignUpType;
use App\Form\UpdateType;
use App\Repository\UserRepository;
use App\Service\ProfileCodeRedirectorInterface;
use App\Service\RandomStringGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends AbstractController
{
    #[Route('/users/{code}/update', name: 'update_user', methods: ['GET', 'POST'])]
    public function updateUser(string                         $code,
                               UserRepository                 $repository,
                               ProfileCodeRedirectorInterface $profileCodeRedirector): Response
    {
        if (!$user = $repository->findByProfileCode($code)) {
            $this->addFlash('error', 'User not found');
            return $this->redirectToRoute('users_list');
        }
        if ($profileCodeRedirector->isRedirectableWithCustomProfileCode($user, $code)) {
            return $profileCodeRedirector->redirectWithCustomProfileCode('update_user');
        }
        $signInForm = $this->createForm(SignInType::class, $user, [
            'method' => 'POST',
            'action' => $this->generateUrl('sign_in')
        ]);
        $signUpForm = $this->createForm(SignUpType::class, $user, [
            'method' => 'POST',
            'action' => $this->generateUrl('sign_up')
        ]);
        $update = $this->createForm(UpdateType::class, $user, [
            'method' => 'POST',
            'action' => $this->generateUrl('update_user', ['code' => $user->getCustomProfileCode() ?? $user->getDefaultProfileCode()])
        ]);

        return $this->render('user/profile-update.html.twig', [
            'signInForm' => $signInForm,
            'signUpForm' => $signUpForm,
            'form' => $update
        ]);
    }

    #[IsGranted(new Expression('(not subject.hasRole("ROLE_ADMIN")) and (is_granted("ROLE_ADMIN") or subject.getId() == subject.getId())'), 'user')]
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