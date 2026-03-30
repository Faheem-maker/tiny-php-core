<?php

namespace framework\models\attributes;

use Attribute;
use framework\models\interfaces\BeforeSave;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Hashed implements BeforeSave
{
    public function beforeSave(&$value, $model)
    {
        $value = password_hash($value, PASSWORD_DEFAULT);
    }
}