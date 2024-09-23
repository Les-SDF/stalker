<?php

namespace App\EventListener;

use App\Entity\User;
use App\Enum\Gender;
use App\Enum\Visibility;
use App\Service\GeolocationServiceInterface;
use App\Service\UserManagerInterface;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Random\RandomException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;


#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: User::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: User::class)]
readonly class UserListener
{
    public function __construct(#[Autowire('%geolocation_enabled%')]
                                private bool                        $geolocationEnabled,
                                private GeolocationServiceInterface $geolocationService,
                                private UserManagerInterface        $userManager
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
    public function prePersist(User $user, PrePersistEventArgs $args): void
    {
        if (is_null($user->getVisibility())) {
            $user->setVisibility(Visibility::Public);
        }
        if (is_null($user->getGender())) {
            $user->setGender(Gender::Unspecified);
        }
        $user->setCreatedAt(new DateTimeImmutable());
        $user->setConnectedAt(new DateTimeImmutable());

        $this->userManager->generateDefaultProfileCode($user);

        if ($this->geolocationEnabled) {
            $user->setCountryCode(
                countryCode: $this->geolocationService->getCountryCodeFromIp(
                    ip: $_SERVER['REMOTE_ADDR']
                )
            );
        }
    }

    public function preUpdate(User $user, PreUpdateEventArgs $args): void
    {
        $user->setEditedAt(new DateTimeImmutable());
    }
}