<?php

declare(strict_types=1);

/**User: Celio Natti... */

namespace natoxCore\validators;

/**
 * Class MatchesValidator
 * 
 * @author Celio Natti <Celionatti@gmail.com>
 * @package natoxCore\validtors
 */

class MatchesValidator extends Validator
{
    public function runValidation()
    {
        $value = $this->_obj->{$this->field};
        return $value == $this->rule;
    }
}
