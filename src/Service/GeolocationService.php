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
    public function __construct(private HttpClientInterface $client,
                                #[Autowire('%geo_api_key%')]
                                private string              $geoApiKey)
    {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getCountryCode(): ?string
    {
        if (PHP_SAPI == 'cli') {
            return 'FR';
        }
        $response = $this->client->request('GET', 'https://api.ipgeolocation.io/ipgeo', [
            'query' => [
                'apiKey' => $this->geoApiKey,
                'ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            ],
        ]);

        return $response->toArray()['country_code2'] ?? 'FR'; // ISO Alpha-2 code
    }
}