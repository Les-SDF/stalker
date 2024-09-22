<?php

namespace App\Service;

use Random\RandomException;
use RangeException;

class RandomStringGenerator implements RandomStringGeneratorInterface
{
    /**
     * https://stackoverflow.com/questions/4356289/php-random-string-generator/31107425#31107425
     *
     * @throws RandomException
     */
    public function generate(int $length = 16): string
    {
        if ($length < 1) {
            throw new RangeException("Length must be a positive integer");
        }
        $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces[] = $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces ?? []);
    }
}