<?php

namespace framework\web\components;

use framework\utils\FileLogger;
use framework\Component;

class Logger extends Component
{
    protected $logger;

    public function init(): void
    {
        $this->logger = new FileLogger(app()->path->resolve('@runtime/logs'));
    }

    public function log($level, $message)
    {
        $this->logger->log($level, $message);
    }

    public function info(string $message): void
    {
        $this->log('INFO', $message);
    }
    public function error(string $message): void
    {
        $this->log('ERROR', $message);
    }
    public function debug(string $message): void
    {
        $this->log('DEBUG', $message);
    }
}