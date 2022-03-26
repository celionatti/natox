<?php

declare(strict_types=1);

/**User: Celio Natti... */

namespace NatoxCore\validators;

/**
 * Class RequiredValidator
 * 
 * @author Celio Natti <Celionatti@gmail.com>
 * @package natoxCore\validtors
 */

class RequiredValidator extends Validator
{
    public function runValidation()
    {
        $value = trim($this->_obj->{$this->field});
        $passes = $value != '' && isset($value);
        return $passes;
    }
}
