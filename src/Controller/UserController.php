<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\Gender;
use App\Form\SignInType;
use App\Form\SignUpType;
use App\Form\UpdateType;
use App\Repository\UserRepository;
use App\Service\ProfileCodeRedirectorInterface;
use App\Service\UserManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Random\RandomException;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends AbstractController
{
    #[Route('/users', name: 'users_list', methods: ['GET'])]
    public function usersList(): Response
    {
        return $this->redirectToRoute('homepage');
    }

    #[Route('/users/{code}/update', name: 'update_user', methods: ['GET', 'POST'])]
    public function updateUser(string                         $code,
                               UserRepository                 $repository,
                               ProfileCodeRedirectorInterface $profileCodeRedirector): Response
    {
        if (!$user = $repository->findByProfileCode($code)) {
            $this->addFlash('error', 'User not found');
            return $this->redirectToRoute('homepage');
        }
        $this->denyAccessUnlessGranted("USER_EDIT", $user);
        if ($profileCodeRedirector->isRedirectableWithCustomProfileCode($user, $code)) {
            return $profileCodeRedirector->redirectToRouteWithCustomProfileCode('update_user');
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
            'action' => $this->generateUrl('update_user', ['code' => $user->getCustomProfileCode() ?? $user->getDefaultProfileCode()]),
        ]);

        $client = HttpClient::create();
        $response = $client->request('GET', 'https://restcountries.com/v3.1/all');
        $countries = $response->toArray();

        $languages = [];
        foreach ($countries as $country) {
            if (isset($country['languages'])) {
                foreach ($country['languages'] as $code => $language) {
                    $languages[$country['cca2']] = [
                        'language' => $language,
                        'countryCode' => $country['cca2'] ?? 'US',
                    ];
                }
            }
        }


        return $this->render('user/profile-update.html.twig', [
            'signInForm' => $signInForm,
            'signUpForm' => $signUpForm,
            'form' => $update,
            'country_codes' => $languages
        ]);
    }

    #[Route('/users/{code}/delete', name: 'delete_user', options: ['expose' => true], methods: ['DELETE'])]
    public function deleteUser(string                 $code,
                               UserRepository         $repository,
                               EntityManagerInterface $entityManager): Response
    {
        if (!$user = $repository->findByProfileCode($code)) {
            $this->addFlash('error', 'User not found');
            return $this->redirectToRoute('homepage');
        }
        $this->denyAccessUnlessGranted("USER_DELETE", $user);
        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', 'User deleted successfully');
        return $this->redirectToRoute('homepage');
    }

    /**
     * @throws RandomException
     */
    #[Route('/users/{code}/reset-profile-code', name: 'reset_default_profile_code', options: ['expose' => true], methods: ['POST'])]
    public function resetDefaultProfileCode(string                         $code,
                                            UserRepository                 $repository,
                                            ProfileCodeRedirectorInterface $profileCodeRedirector,
                                            UserManagerInterface           $userManager): Response
    {
        if (!$user = $repository->findByProfileCode($code)) {
            $this->addFlash('error', 'User not found');
            return $this->redirectToRoute('homepage');
        }
        $this->denyAccessUnlessGranted("USER_RESET_DEFAULT_PROFILE_CODE", $user);
        if ($profileCodeRedirector->isRedirectableWithCustomProfileCode($user, $code)) {
            return $profileCodeRedirector->redirectToRouteWithCustomProfileCode('reset_default_profile_code');
        }
        $userManager->generateDefaultProfileCode($user);

        return $this->redirectToRoute('update_user', ['code' => $user->getCustomProfileCode() ?? $user->getDefaultProfileCode()]);
    }
}