<?php

namespace framework\models\interfaces;

interface BeforeSave
{
    public function beforeSave(&$value, $model);
}