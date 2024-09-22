<?php

namespace App\Service;

use App\Entity\User;
use LogicException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;

readonly class ProfileCodeRedirector implements ProfileCodeRedirectorInterface
{
    /**
     * @var User $user
     */
    private UserInterface $user;

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function isRedirectableWithCustomProfileCode(UserInterface $user,
                                                        string        $code): bool
    {
        /**
         * On pourrait récupérer l'user depuis son code, mais on évite ainsi de faire une requête inutile à la base de données
         */
        $this->user = $user;
        return $this->user->getDefaultProfileCode() === $code && $this->user->getCustomProfileCode();
    }

    public function redirectToRouteWithCustomProfileCode(string $routeName): RedirectResponse
    {
        if (!$this->user->getCustomProfileCode()) {
            throw new LogicException("User has no custom profile code");
        }
        return new RedirectResponse(
            url: $this->urlGenerator->generate($routeName, [
                'code' => $this->user->getCustomProfileCode()
            ])
        );
    }
}