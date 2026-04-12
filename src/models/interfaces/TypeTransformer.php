<?php

namespace framework\models\interfaces;

interface TypeTransformer
{
    public function transformFromDatabase($value);
    public function transformToDatabase($value);
}
