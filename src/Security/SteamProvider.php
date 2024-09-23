<?php

namespace App\Security;

use League\OAuth2\Client\Provider\GenericProvider;

class SteamProvider extends GenericProvider
{
    public function __construct(array $options = [])
    {
        $options['urlAccessToken'] = '';
        $options['urlResourceOwnerDetails'] = '';
        $options['urlAuthorize'] = 'https://steamcommunity.com/openid/login?' . http_build_query([
                'openid.ns' => 'http://specs.openid.net/auth/2.0',
                'openid.mode' => 'checkid_setup',
                'openid.return_to' => 'http://localhost/public/steam/check',
                'openid.realm' => 'http://localhost',
                'openid.claimed_id' => 'http://specs.openid.net/auth/2.0/identifier_select',
                'openid.identity' => 'http://specs.openid.net/auth/2.0/identifier_select',
            ]);
        parent::__construct($options);
    }
}

