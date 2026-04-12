<?php

namespace framework\models\transformers;

use framework\models\interfaces\TypeTransformer;

class DateTimeTransformer implements TypeTransformer
{
    public function transformFromDatabase($value)
    {
        if (empty($value)) return null;
        return new \DateTime($value);
    }

    public function transformToDatabase($value)
    {
        if ($value instanceof \DateTime) {
            return $value->format('Y-m-d H:i:s');
        }
        return $value;
    }
}
