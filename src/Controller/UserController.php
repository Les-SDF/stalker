<?php

namespace App\Controller;

use App\Form\UpdateType;
use App\Repository\UserRepository;
use App\Service\CountryService;
use App\Service\FlashMessageHelper;
use App\Service\UserManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Random\RandomException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/users', name: 'users_list', methods: ['GET'])]
    public function usersList(): Response
    {
        return $this->redirectToRoute('homepage');
    }

    #[Route('/users/{profileCode}/update', name: 'update_user', methods: ['GET', 'POST'])]
    public function updateUser(string                 $profileCode,
                               UserRepository         $repository,
                               CountryService         $country,
                               Request                $request,
                               EntityManagerInterface $entityManager,
                               FlashMessageHelper     $flashMessageHelper): Response
    {
        if (!$user = $repository->findByProfileCode($profileCode)) {
            $this->addFlash('error', 'User not found');
            return $this->redirectToRoute('homepage');
        }
        $this->denyAccessUnlessGranted("USER_EDIT", $user);

        $countries = $country->getCountries();
        $form = $this->createForm(UpdateType::class, $user, [
            'method' => 'POST',
            'action' => $this->generateUrl('update_user', [
                'profileCode' => $user->getProfileCode()
            ]),
            'countries' => $countries,
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('user_profile', ['profileCode' => $user->getProfileCode()]);
        } else {
            $flashMessageHelper->addFormErrorsAsFlashMessages($form);
        }
        return $this->render('user/profile-update.html.twig', [
            'form' => $form,
            'country_codes' => $countries
        ]);
    }

    #[Route('/users/{profileCode}/delete', name: 'delete_user', options: ['expose' => true], methods: ['DELETE'])]
    public function deleteUser(string                 $profileCode,
                               UserRepository         $repository,
                               EntityManagerInterface $entityManager): Response
    {
        if (!$user = $repository->findByProfileCode($profileCode)) {
            $this->addFlash('error', 'User not found');
            return $this->redirectToRoute('homepage');
        }
        $this->denyAccessUnlessGranted("USER_DELETE", $user);
        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', 'User deleted successfully');
        return $this->redirectToRoute('homepage');
    }

    #[Route('/users/{profileCode}/update-profile-code', name: 'update_profile_code', options: ['expose' => true], methods: ['POST'])]
    public function updateProfileCode(string         $profileCode,
                                      UserRepository $repository): Response
    {
        if (!$user = $repository->findByProfileCode($profileCode)) {
            $this->addFlash('error', 'User not found');
            return $this->redirectToRoute('homepage');
        }
        $this->denyAccessUnlessGranted("USER_EDIT", $user);

        // TODO: Récuperer l'utilisateur et lui donner le nouveau code

        return $this->redirectToRoute('user_profile', [
            'profileCode' => $user->getProfileCode()
        ]);
    }

    #[Route('/users/check-profile-code-availability', name: 'check_profile_code_availability', options: ['expose' => true], methods: ['POST'])]
    public function checkProfileCodeAvailability(Request              $request,
                                                 UserManagerInterface $userManager): Response
    {
        return $this->json([
            'is_available' => $userManager->isProfileCodeAvailable(
                profileCode: json_decode($request->getContent(), true)['profileCode']
            )
        ]);
    }

    /**
     * @throws RandomException
     */
    #[Route('/users/{profileCode}/reset-profile-code', name: 'reset_profile_code', options: ['expose' => true], methods: ['POST'])]
    public function resetDefaultProfileCode(string               $profileCode,
                                            UserRepository       $repository,
                                            UserManagerInterface $userManager): Response
    {
        if (!$user = $repository->findByProfileCode($profileCode)) {
            $this->addFlash('error', 'User not found');
            return $this->redirectToRoute('homepage');
        }
        $this->denyAccessUnlessGranted("USER_EDIT", $user);
        $userManager->generateProfileCode($user);

        return $this->redirectToRoute('update_user', [
            'profileCode' => $user->getProfileCode()
        ]);
    }
}