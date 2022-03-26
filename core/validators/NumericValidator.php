<?php

declare(strict_types=1);

/**User: Celio Natti... */

namespace natoxCore\validators;

/**
 * Class NumericValidator
 * 
 * @author Celio Natti <Celionatti@gmail.com>
 * @package natoxCore\validtors
 */

class NumericValidator extends Validator
{
    public function runValidation()
    {
        $value = $this->_obj->{$this->field};
        $pass = true;
        if (!empty($value)) {
            $pass = is_numeric($value);
        }
        return $pass;
    }
}
