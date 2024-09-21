<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class GeolocationService implements GeolocationServiceInterface
{
    public function __construct(private HttpClientInterface               $client,
                                #[Autowire('%geo_api_key%')] private string $geoApiKey)
    {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getCountryCodeFromIp(string $ip): ?string
    {
        $response = $this->client->request('GET', 'https://api.ipgeolocation.io/ipgeo', [
            'query' => [
                'apiKey' => $this->geoApiKey,
                'ip' => $ip,
            ],
        ]);

        return $response->toArray()['country_code2'] ?? null; // ISO Alpha-2 code
    }
}