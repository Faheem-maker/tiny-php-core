<?php

namespace framework\db\commands;

use framework\db\drivers\BaseDriver;
use framework\db\traits\HasTable;
use framework\db\traits\HasWhere;

class UpdateCommand extends BaseCommand
{
    protected $cols;
    protected $table;
    protected $params = [];

    use HasWhere;
    use HasTable;

    public function __construct(BaseDriver $driver, $table, $cols)
    {
        $this->table = $table;
        $this->cols = $cols;
        parent::__construct($driver);
    }

    public function sql(): string
    {
        return $this->conn->compile('update', [
            'table' => $this->table,
            'columns' => $this->cols,
            'where' => $this->where,
        ]);
    }

    public function execute(): \framework\db\QueryResult
    {
        $sql = $this->sql();

        // Add parameters
        foreach ($this->cols as $key => $col) {
            $this->params[":$key"] = $col;
        }

        return $this->conn->execute($sql, $this->params);
    }
}