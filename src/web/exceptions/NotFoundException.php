<?php

namespace framework\web\exceptions;

use Throwable;

class NotFoundException extends HttpException {
    public function __construct(string $message = "", ?Throwable $previous = null)
    {
        return parent::__construct($message, 404, $previous);
    }
}