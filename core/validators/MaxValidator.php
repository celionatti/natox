<?php

declare(strict_types=1);

/**User: Celio Natti... */

namespace NatoxCore\validators;

/**
 * Class MaxValidator
 * 
 * @author Celio Natti <Celionatti@gmail.com>
 * @package natoxCore\validtors
 */

class MaxValidator extends Validator
{
    public function runValidation()
    {
        $value = $this->_obj->{$this->field};
        $pass = strlen($value) <= $this->rule;
        return $pass;
    }
}
