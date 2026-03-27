<?php

namespace framework\web;

use framework\contracts\ComponentInterface;

abstract class Component implements ComponentInterface
{
    public function init(): void
    {
    }
}