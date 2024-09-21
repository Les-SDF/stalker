<?php

namespace App\EventSubscriber;

use App\Entity\User;
use DateTimeImmutable;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Security\Http\Event\LogoutEvent;

readonly class AuthenticationSubscriber
{
    public function __construct(private RequestStack $requestStack){}

    #[AsEventListener]
    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        /**
         * @var User $user
         */
        $user = $event->getUser();
        $user->setConnectedAt(new DateTimeImmutable());
        $flashBag = $this->requestStack->getSession()->getFlashBag();
        $flashBag->add("success", "You are signed in.");
    }

    #[AsEventListener]
    public function onLoginFailure(LoginFailureEvent $event): void
    {
        $flashBag = $this->requestStack->getSession()->getFlashBag();
        $flashBag->add("error", "Invalid credentials.");
    }

    #[AsEventListener]
    public function onLogout(LogoutEvent $event): void
    {
        /**
         * @var User $user
         */
        $user = $event->getToken()->getUser();
        $user->setConnectedAt(new DateTimeImmutable());
        $flashBag = $this->requestStack->getSession()->getFlashBag();
        $flashBag->add("success", "You are signed out.");
    }
}