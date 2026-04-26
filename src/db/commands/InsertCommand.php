<?php

namespace framework\db\commands;

use framework\db\drivers\BaseDriver;

class InsertCommand extends BaseCommand
{
    protected $cols;
    protected $table;
    protected $params = [];

    public function __construct(BaseDriver $driver, $table, $cols)
    {
        $this->table = $table;
        $this->cols = $cols;
        parent::__construct($driver);
    }

    public function sql(): string
    {
        return $this->conn->compile('insert', [
            'table' => $this->table,
            'columns' => $this->cols,
        ]);
    }

    public function execute()
    {
        $sql = $this->sql();

        // Add parameters
        foreach ($this->cols as $key => $col) {
            $this->params[":$key"] = $col;
        }

        return $this->conn->execute($sql, $this->params);
    }
}