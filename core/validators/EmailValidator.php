<?php

declare(strict_types=1);

/**User: Celio Natti... */

namespace NatoxCore\validators;

/**
 * Class EmailValidator
 * 
 * @author Celio Natti <Celionatti@gmail.com>
 * @package natoxCore\validtors
 */

class EmailValidator extends Validator
{
    public function runValidation()
    {
        $email = $this->_obj->{$this->field};
        $pass = true;
        if (!empty($email)) {
            $pass = filter_var($email, FILTER_VALIDATE_EMAIL);
        }
        return $pass;
    }
}
