<?php

namespace App\EventListener;

use App\Entity\User;
use App\Service\GeolocationServiceInterface;
use App\Service\RandomStringGeneratorInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Random\RandomException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;


#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: User::class)]
readonly class UserListener
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
    public function prePersist(User $user, PrePersistEventArgs $event): void
    {
        // Call the geolocation service to set the country code
        $ip = $_SERVER['REMOTE_ADDR']; // Or get this from the request context
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