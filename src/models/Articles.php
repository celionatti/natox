<?php

namespace Natox\models;

use NatoxCore\Model;
use NatoxCore\validators\RequiredValidator;


/**
 * Class Articles
 * 
 * @author Celio Natti <amisuusman@gmail.com>
 * @package Natox\model
 */

class Articles extends Model
{
    protected static $table = "articles";
    public $id, $created_at, $updated_at, $user_id, $title, $body, $img, $status = 'private', $category_id = 0;

    public function beforeSave()
    {
        $this->timeStamps();
        $this->runValidation(new RequiredValidator($this, ['field' => 'title', 'msg' => 'Title is a required field']));
        $this->runValidation(new RequiredValidator($this, ['field' => 'body', 'msg' => 'Body is a required field.']));
    }
}
