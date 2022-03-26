<?php

declare(strict_types=1);

/**User: Celio Natti *** */

namespace natoxCore\helpers;

/**
 * Class TextContent
 * 
 * @author Celio Natti <amisuusman@gmail.com>
 * @package NatoxCore/helpers
 */

abstract class TextContent
{
    /**
     * Excerpt Function
     * 
     * This function helps short Text Content;
     * Accept Two Params
     * @param [type] $text for the variable to short
     * @param int|string $length for number of length
     *
     * @return mixed
     */
    public static function Excerpt($text, $length)
    {
        return substr($text, 0, $length) . '...';
    }
}
