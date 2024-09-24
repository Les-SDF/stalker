<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CountryService implements CountryServiceInterface
{
    const COUNTRIES_API = 'https://restcountries.com/v3.1/all';

    public function __construct(private HttpClientInterface $client)
    {
    }

    public function getCountries(): array
    {
        $response = $this->client->request('GET', self::COUNTRIES_API);
        $countries = $response->toArray();
        $countriesList = [];

        foreach ($countries as $country) {
            $countriesList[] = [
                $country['cca2'] => $country['name']['common']
            ];
        }
        dd($countriesList);

        uasort($countriesList, function ($a, $b) {
            return strcmp($a['country'], $b['country']);
        });

        return $countriesList;
    }

}