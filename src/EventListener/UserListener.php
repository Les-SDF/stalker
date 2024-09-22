<?php

namespace App\EventListener;

use App\Entity\User;
use App\Service\GeolocationServiceInterface;
use App\Service\RandomStringGeneratorInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Random\RandomException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;


#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: User::class)]
readonly class UserListener
{
    public function __construct(#[Autowire('%geolocation_enabled%')]
                                private bool                           $geolocationEnabled,
                                private GeolocationServiceInterface    $geolocationService,
                                private RandomStringGeneratorInterface $randomStringGenerator,
    )
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
        if ($this->geolocationEnabled) {
            $ip = $_SERVER['REMOTE_ADDR'];
            $countryCode = $this->geolocationService->getCountryCodeFromIp($ip);
        }
        $user->setCountryCode($countryCode ?? 'FR');

        // Moyen plus sécurisé de générer un code aléatoire
        $user->setDefaultProfileCode(
            $this->randomStringGenerator->generate()
        );
    }
}