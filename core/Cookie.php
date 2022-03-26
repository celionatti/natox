<?php

declare(strict_types=1);

/**User: Celio Natti... */

namespace NatoxCore;


/**
 * Class Cookie
 * 
 * @author Celio Natti <amisuusman@gmail.com>
 * @package NatoxCore
 */

class Cookie
{
    public static function get($name)
    {
        if (self::exists($name)) {
            return $_COOKIE[$name];
        }
        return false;
    }

    public static function set($name, $value, $expiry)
    {
        if (setCookie($name, $value, time() + $expiry, '/', "", false, true)) {
            return true;
        }
        return false;
    }

    public static function delete($name)
    {
        return self::set($name, '', -1);
    }

    public static function exists($name)
    {
        return isset($_COOKIE[$name]);
    }
}