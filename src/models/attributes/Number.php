<?php

namespace framework\models\attributes;

use Attribute;
use framework\contracts\Validator;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD)]
class Number extends Validator
{
    public $message = 'This must be a valid number';

    public function validate($value, $_ = null): bool
    {
        return is_numeric($value);
    }

}