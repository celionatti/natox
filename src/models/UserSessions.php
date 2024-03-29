<?php

/**User: Celio Natti... */

namespace Natox\models;

use NatoxCore\Model;

/**
 * Class UserSessions
 * 
 * @author Celio Natti <amisuusman@gmail.com>
 * @package Natox\models
 */

class UserSessions extends Model
{
    protected static $table = 'user_sessions';
    public $id, $user_id, $hash;

    public static function findByUserId($user_id)
    {
        return self::findFirst([
            'conditions' => "user_id = :user_id",
            'bind' => ['user_id' => $user_id]
        ]);
    }

    public static function findByHash($hash)
    {
        return self::findFirst([
            'conditions' => "hash= :hash",
            'bind' => ['hash' => $hash]
        ]);
    }
}
