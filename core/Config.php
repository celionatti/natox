<?php

declare(strict_types=1);

/**User: Celio Natti... */

namespace NatoxCore;

/**
 * Class Config
 * 
 * @author Celio Natti <amisuusman@gmail.com>
 * @package NatoxCore
 */

class Config
{
    private static $config = [
        'version'               => '1.0.0',
        'default_controller'    => 'Blog', // The default home controller
        'default_site_title'    => 'Natox', // Default Site title
        'APP_KEY'               =>  'natoxKey',
    ];

    public static function get($key)
    {
        if (array_key_exists($key, $_ENV)) return $_ENV[$key];
        return array_key_exists($key, self::$config) ? self::$config[$key] : NULL;
    }
}
