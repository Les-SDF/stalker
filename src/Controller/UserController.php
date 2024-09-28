<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UpdateCodeType;
use App\Form\UpdateType;
use App\Service\CountryService;
use App\Service\FlashMessageHelper;
use App\Service\FlashMessageHelperInterface;
use App\Service\UserManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Random\RandomException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    #[IsGranted('ROLE_USER')]
    #[Route('/account/update', name: 'update_user', methods: ['GET', 'POST'])]
    public function updateUser(CountryService         $country,
                               Request                $request,
                               EntityManagerInterface $entityManager,
                               FlashMessageHelper     $flashMessageHelper, UserManagerInterface $userManager): Response
    {
        /**
         * @var $user User
         */
        $user = $this->getUser();
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
            $userManager->storeProfilePicture($user, $form['profilePicture']->getData());
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('user_profile', [
                'profileCode' => $user->getProfileCode()
            ]);
        } else {
            $flashMessageHelper->addFormErrorsAsFlashMessages($form);
        }
        return $this->render('user/profile-update.html.twig', [
            'form' => $form,
            'country_codes' => $countries
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/account/delete', name: 'delete_user', options: ['expose' => true], methods: ['DELETE','GET'])]
    public function deleteUser(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $this->denyAccessUnlessGranted("USER_DELETE", $user);
        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', 'User deleted successfully');
        return $this->redirectToRoute('homepage');
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/account/update-profile-code', name: 'update_profile_code', options: ['expose' => true], methods: ['POST'])]
    public function updateProfileCode(EntityManagerInterface      $entityManager,
                                      Request                     $request,
                                      FlashMessageHelperInterface $flashMessageHelper): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $this->denyAccessUnlessGranted("USER_EDIT", $user);

        $form = $this->createForm(UpdateCodeType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newProfileCode = $form->get('profileCode')->getData();
            $user->setProfileCode($newProfileCode);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Profile code updated successfully');
            return $this->redirectToRoute('user_profile', [
                'profileCode' => $user->getProfileCode()
            ]);
        } else {
            $flashMessageHelper->addFormErrorsAsFlashMessages($form);
        }

        return $this->redirectToRoute('user_profile', [
            'profileCode' => $user->getProfileCode()
        ]);
    }

    #[Route('/api/users/check-profile-code-availability', name: 'check_profile_code_availability', options: ['expose' => true], methods: ['POST'])]
    public function checkProfileCodeAvailability(Request $request, UserManagerInterface $userManager): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

            if (empty($data['profileCode'])) {
                return $this->json(['error' => 'Profile code not provided'], Response::HTTP_BAD_REQUEST);
            }

            $isAvailable = $userManager->isProfileCodeAvailable($data['profileCode']);

            return $this->json(['is_available' => $isAvailable], Response::HTTP_OK);
        } catch (JsonException $e) {
            return $this->json(['error' => 'Invalid JSON: ' . $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return $this->json(['error' => 'An error occurred: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @throws RandomException
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/users/{profileCode}/reset-profile-code', name: 'reset_profile_code', options: ['expose' => true], methods: ['POST'])]
    public function resetDefaultProfileCode(UserManagerInterface $userManager): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $this->denyAccessUnlessGranted("USER_EDIT", $user);
        $userManager->generateProfileCode($user);

        return $this->redirectToRoute('update_user', [
            'profileCode' => $user->getProfileCode()
        ]);
    }
}