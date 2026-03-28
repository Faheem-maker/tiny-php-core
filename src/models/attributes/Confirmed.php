<?php

namespace framework\web\models\attributes;

use Attribute;
use framework\contracts\Validator;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Confirmed extends Validator
{
    public $message = 'The value must match its confirmation value';

    public function __construct(protected $confirmation, $message = '')
    {
        return parent::__construct($message);
    }

    public function validate($value, $doc = null): bool
    {
        return $doc[$this->confirmation] == $value;
    }

}