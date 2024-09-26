<?php

namespace App\Service;

interface CountryServiceInterface
{
    public function getCountries(): array;
    public function getCountryName(string $countryCode): string;
}