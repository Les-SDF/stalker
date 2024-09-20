<?php

namespace App\Service;

use Random\RandomException;

class RandomStringGenerator implements RandomStringGeneratorInterface
{
    /**
     * @throws RandomException
     */
    public function generate(int $length = 16): string
    {
        if ($length < 1) {
            throw new \RangeException("Length must be a positive integer");
        }
        $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces[] = $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }
}