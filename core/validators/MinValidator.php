<?php

declare(strict_types=1);

/**User: Celio Natti... */

namespace natoxCore\validators;

/**
 * Class MinValidator
 * 
 * @author Celio Natti <Celionatti@gmail.com>
 * @package natoxCore\validtors
 */

class MinValidator extends Validator
{
    public function runValidation()
    {
        $value = $this->_obj->{$this->field};
        $pass = strlen($value) >= $this->rule;
        return $pass;
    }
}
