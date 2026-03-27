<?php

namespace framework\db\commands;

abstract class BaseCommand {
    protected $conn;
    protected $components = [];

    public function __construct($conn) {
        $this->conn = $conn;
    }
}