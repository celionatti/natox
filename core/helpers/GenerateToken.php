<?php

namespace natoxCore\helpers;

use natoxCore\Config;

/**
 * Class GenerateToken
 * 
 * @author Celio Natti <amisuusman@gmail.com>
 * @package NatoxCore/helpers
 */

abstract class GenerateToken
{
    public static $seed;
    public static $time;
    public static $hash;

    public static function CreateToken()
    {
        self::$seed = random_bytes(8);
        self::$time = time();
        self::$hash = hash_hmac('sha256', session_id() . self::$seed . self::$time, Config::get('CSRF_TOKEN_SECRET'), true);
        return self::UrlSafeEncode(self::$hash . '|' . self::$seed . '|' . self::$time);
    }

    public static function ValidateToken($token)
    {
        $parts = explode('|', self::urlSafeDecode($token));
        if (count($parts) === 3) {
            $hash = hash_hmac('sha256', session_id() . $parts[1] . $parts[2], Config::get('CSRF_TOKEN_SECRET'), true);
            if (hash_equals($hash, self::urlSafeDecode($parts[0]))) {
                return true;
            }
        }
        return false;
    }

    public static function UrlSafeEncode($m)
    {
        return rtrim(strtr(base64_encode($m), '+/', '-_'), '=');
    }

    public static function UrlSafeDecode($m)
    {
        return base64_decode(strtr($m, '-_', '+/'));
    }

    public static function randomString($length)
    {
        $array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

        $text = "";

        for ($i = 0; $i < $length; $i++) {
            $random = rand(0, 61);
            $text .= $array[$random];
        }
        return $text;
    }

    public static function randomPassword()
    {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
}
