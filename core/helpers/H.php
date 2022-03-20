<?php

/**User: Celio Natti... */

declare(strict_types=1);

namespace NatoxCore\helpers;

/**
 * Class H
 * 
 * @author Celio Natti <Celionatti@gmail.com>
 * @package natoxCore\helpers
 */

class H
{
    public static function dnd($data = [], $die = true)
    {
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
        if ($die) {
            die;
        }
    }
}