<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Service\GeolocationServiceInterface;
use App\Service\RandomStringGeneratorInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Random\RandomException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

readonly class UserEntityListener
{

    public function __construct(private GeolocationServiceInterface $geolocationService,
                                private RandomStringGeneratorInterface $randomStringGenerator)
    {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws RandomException
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function prePersist(User $user, LifecycleEventArgs $event): void
    {
        // Récupérer l'IP depuis l'EventArgs ou d'un service HTTP
        $request = $event->getEntityManager()->getConfiguration()->getCustomHydrationMode('request'); // Pas possible ici
        $ip = $request->getClientIp(); // à définir selon ton code

        // Appeler le service de géolocalisation
        $countryCode = $this->geolocationService->getCountryCodeFromIp($ip);

        if ($countryCode) {
            $user->setCountryCode($countryCode);
        }

        // Moyen plus sécurisé pour générer un code aléatoire
        $user->setDefaultProfileCode(
            $this->randomStringGenerator->generate()
        );
    }
}