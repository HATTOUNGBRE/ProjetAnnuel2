<?php
// src/Utils/ReservationNumberGenerator.php

namespace App\Utils;

class ReservationNumberGenerator
{
    public static function generate(): string
    {
        return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10 / strlen($x)))), 1, 10);
    }
}
