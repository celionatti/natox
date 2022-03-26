<?php

use NatoxCore\Model;
use NatoxCore\validators\MinValidator;
use NatoxCore\validators\EmailValidator;
use NatoxCore\validators\UniqueValidator;
use NatoxCore\validators\MatchesValidator;
use NatoxCore\validators\RequiredValidator;

/**
 * Class Users
 * 
 * @author Celio Natti <amisuusman@gmail.com>
 * @package Natox\model
 */

 class Users extends Model
 {
    protected static $table = "users", $_current_user = false;
    public  $id, $created_at, $updated_at, $fname, $lname, $email, $password, $acl, $blocked = 0, $img, $verified = 0, $pin = '0000', $ip, $auth_code, $confirmPassword, $remember = '';

    const AUTHOR_PERMISSION = 'author';
    const ADMIN_PERMISSION = 'admin';

    public function beforeSave()
    {
        $this->timeStamps();

        $this->runValidation(new RequiredValidator($this, ['field' => 'fname', 'msg' => "First Name is a required field."]));
        $this->runValidation(new RequiredValidator($this, ['field' => 'lname', 'msg' => "Last Name is a required field."]));
        $this->runValidation(new RequiredValidator($this, ['field' => 'email', 'msg' => "Email is a required field."]));
        $this->runValidation(new EmailValidator($this, ['field' => 'email', 'msg' => 'You must provide a valid email.']));
        $this->runValidation(new UniqueValidator($this, ['field' => ['email', 'acl', 'lname'], 'msg' => 'A user with that email address already exists.']));
        $this->runValidation(new RequiredValidator($this, ['field' => 'acl', 'msg' => "Role is a required field."]));

        if ($this->isNew() || $this->resetPassword) {
            $this->runValidation(new RequiredValidator($this, ['field' => 'password', 'msg' => "Password is a required field."]));
            $this->runValidation(new RequiredValidator($this, ['field' => 'confirmPassword', 'msg' => "Confirm Password is a required field."]));
            $this->runValidation(new MatchesValidator($this, ['field' => 'confirmPassword', 'rule' => $this->password, 'msg' => "Your passwords do not match."]));
            $this->runValidation(new MinValidator($this, ['field' => 'password', 'rule' => 8, 'msg' => "Password must be at least 8 characters."]));
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        } else {
            $this->_skipUpdate = ['password'];
        }
    }

    public function validateLogin()
    {
        $this->runValidation(new RequiredValidator($this, ['field' => 'email', 'msg' => "Email is a required field."]));
        $this->runValidation(new RequiredValidator($this, ['field' => 'password', 'msg' => "Password is a required field."]));
    }
 }