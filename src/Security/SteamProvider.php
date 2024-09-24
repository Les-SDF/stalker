<?php

namespace App\Security;

use League\OAuth2\Client\Provider\GenericProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SteamProvider extends GenericProvider
{
    private UrlGeneratorInterface $urlGenerator;
    private array $options = [];

    public function __construct(UrlGeneratorInterface $urlGenerator, array $options = [], array $collaborators = [])
    {
        $this->urlGenerator = $urlGenerator;

        // Configure les URLs nécessaires
        $options['urlAccessToken'] = '';
        $options['urlResourceOwnerDetails'] = '';
        $options['urlAuthorize'] = 'https://steamcommunity.com/openid/login?' . http_build_query([
                'openid.ns' => 'http://specs.openid.net/auth/2.0',
                'openid.mode' => 'checkid_setup',
                'openid.return_to' => $this->urlGenerator->generate('steam_check', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'openid.realm' => 'http://localhost',
                'openid.claimed_id' => 'http://specs.openid.net/auth/2.0/identifier_select',
                'openid.identity' => 'http://specs.openid.net/auth/2.0/identifier_select',
            ]);

        $this->options = $options;

        parent::__construct($options, $collaborators);
    }

    public function redirect(): RedirectResponse
    {
        return new RedirectResponse($this->options['urlAuthorize']);
    }
}
