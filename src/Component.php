<?php

namespace framework;

use framework\contracts\ComponentInterface;

abstract class Component implements ComponentInterface
{
    public function init(): void
    {
    }
}