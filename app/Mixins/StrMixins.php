<?php

namespace App\Mixins;

class StrMixins
{
    public function partNumber()
    {
        return function ($part) {
            return "AB-" . substr($part, 0, 3) . "-" . substr($part, 3);
        };
    }

    public function prefix()
    {
        return function ($string, $prefix = "AB - ") {
            return $prefix . $string;
        };
    }

    public function generateRandomString()
    {
        return function ($length = 10) {
            return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
        };
    }
}
