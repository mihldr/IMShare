<?php

namespace App\Service;

class RandomService
{

    public static function generateRandomString(int $length = 10, bool $useNumbers = false): string
    {
        # credits: https://stackoverflow.com/a/4356295/10887013

        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if($useNumbers) $characters .= "0123456789";

        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}