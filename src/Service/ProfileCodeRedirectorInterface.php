<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;

interface ProfileCodeRedirectorInterface
{
    public function isRedirectableWithCustomProfileCode(UserInterface $user, string $code): bool;

    public function redirectWithCustomProfileCode(string $routeName): RedirectResponse;
}