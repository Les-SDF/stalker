<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CountryService implements CountryServiceInterface
{
    public function __construct(private HttpClientInterface $client)
    {
    }

    public function getCountries(): array
    {
        $response = $this->client->request('GET', 'https://restcountries.com/v3.1/all');
        $countries = $response->toArray();

        $countriesList = [];

        foreach ($countries as $country) {
            if (!isset($countriesList[$country['cca2']])) {
                $countriesList[$country['cca2']] = [
                    'country' => $country['name']['common'],
                    'countryCode' => $country['cca2'],
                ];
            }
        }

        uasort($countriesList, function ($a, $b) {
            return strcmp($a['country'], $b['country']);
        });

        return $countriesList;
    }

}