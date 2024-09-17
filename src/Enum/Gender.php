<?php

namespace App\Enum;

enum Gender: string
{
    case Unspecified = 'unspecified';
    case Male = 'male';
    case Female = 'female';
    case Other = 'other';
    case PreferNotToSay = 'prefer_not_to_say';
}