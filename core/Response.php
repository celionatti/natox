<?php

declare(strict_types=1);

/**User: Celio Natti... */

namespace NatoxCore;

use Natox\models\Users;

/**
 * Class Response
 * 
 * @author Celio Natti <amisuusman@gmail.com>
 * @package NatoxCore
 */

class Response
{
    public function statusCode(int $code)
    {
        http_response_code($code);
    }

    public static function redirect($location)
    {
        if (!headers_sent()) {
            header('Location: ' . ROOT . $location);
        } else {
            echo '<script type="text/javascript">';
            echo 'window.location.href = "' . ROOT . $location . '"';
            echo '</script>';
            echo '<nosript>';
            echo '<meta http-equiv="refresh" content="0;url=' . ROOT . $location . '" />';
            echo '</nosript>';
        }
        exit();
    }

    public static function permRedirect($perm, $redirect, $msg = "You do not have access to this page.")
    {
        $user = Users::getCurrentUser();
        $allowed = $user && $user->hasPermission($perm);
        if (!$allowed) {
            Session::msg($msg);
            self::redirect($redirect);
        }
    }
}
