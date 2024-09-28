<?php

namespace App\Controller;

use App\Entity\SocialMedia;
use App\Entity\User;
use App\Entity\UserSocialMedia;
use App\Repository\SocialMediaRepository;
use App\Security\SteamProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class SteamController extends AbstractController
{
    #[isGranted('ROLE_USER')]
    #[Route('/steam/connect', name: 'steam_connect')]
    public function connect(SteamProvider $steamClient): RedirectResponse
    {
        return $steamClient->redirect();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[isGranted('ROLE_USER')]
    #[Route('/steam/check', name: 'steam_check')]
    public function check(#[Autowire('%steam_api%')]
                          string $steamApi,
                          Request $request,
                          EntityManagerInterface $entityManager,
                          SocialMediaRepository $mediaRepository): RedirectResponse|JsonResponse
    {
        /**
         * @var User $user
         */
        $requestParams = $request->query->all();

        if (isset($requestParams['openid_claimed_id'])) {
            $claimedId = $requestParams['openid_claimed_id'];
            $steamId = basename($claimedId);

            $url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$steamApi&steamids=$steamId";

            $httpClient = HttpClient::create();
            $response = $httpClient->request('GET', $url);

            if ($response->getStatusCode() === 200) {
                $data = $response->toArray();

                $playerInfo = $data['response']['players'][0];
                $user = $this->getUser();
                $social = $mediaRepository->findOneBy(['name' => 'steam']);

                if (!$social) {
                    $social = new SocialMedia();
                    $social->setName('steam');
                    $entityManager->persist($social);
                    $entityManager->flush();
                }

                $userSocialMedia = new UserSocialMedia();
                $userSocialMedia->setSocialMedia($social);
                $userSocialMedia->setUser($user);
                $userSocialMedia->setUrl($playerInfo['profileurl']);
                $userSocialMedia->setUsername($playerInfo['personaname']);
                $entityManager->persist($userSocialMedia);
                $entityManager->flush();
                return $this->redirectToRoute('user_profile',['profileCode' => $user->getProfileCode()]);
                //return new JsonResponse([$data]);
            }

            return $this->redirectToRoute('homepage');
        }
        return $this->redirectToRoute('error_page');
    }
}