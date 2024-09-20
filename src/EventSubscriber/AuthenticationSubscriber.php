<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class AuthenticationSubscriber
{
    public function __construct(private RequestStack $requestStack){}

    #[AsEventListener]
    public function onLoginSuccess(LoginSuccessEvent $event) {
        $flashBag = $this->requestStack->getSession()->getFlashBag();
        $flashBag->add("success", "You are signed in.");
    }

    #[AsEventListener]
    public function onLoginFailure(LoginFailureEvent $event) {
        $flashBag = $this->requestStack->getSession()->getFlashBag();
        $flashBag->add("error", "Invalid credentials.");
    }

    #[AsEventListener]
    public function onLogout(LogoutEvent $event) {
        $flashBag = $this->requestStack->getSession()->getFlashBag();
        $flashBag->add("success", "You are signed out.");
    }
}