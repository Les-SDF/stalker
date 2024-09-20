<?php

namespace App\Service;

use Random\RandomException;

interface RandomStringGeneratorInterface
{
    /**
     * @throws RandomException
     */
    function generate(int $length = 16): string;
}