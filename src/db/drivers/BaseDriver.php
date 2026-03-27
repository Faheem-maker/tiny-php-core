<?php

namespace framework\db\drivers;

use framework\db\QueryResult;

abstract class BaseDriver
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }
    
    public abstract function connect(): void;
    public abstract function disconnect(): void;
    public abstract function compile(string $type, array $components): string;
    public abstract function execute(string $sql, array $params = []): QueryResult;
    public abstract function lastInsertId(): int;
}