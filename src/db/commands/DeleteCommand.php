<?php

namespace framework\db\commands;

use framework\db\drivers\BaseDriver;
use framework\db\traits\HasTable;
use framework\db\traits\HasWhere;

class DeleteCommand extends BaseCommand
{
    protected $table;
    protected $params = [];

    use HasTable;
    use HasWhere;

    public function __construct(BaseDriver $driver, $table)
    {
        $this->table = $table;
        parent::__construct($driver);
    }

    public function sql(): string
    {
        return $this->conn->compile('delete', [
            'table' => $this->table,
            'where' => $this->where,
        ]);
    }

    public function execute(): \framework\db\QueryResult
    {
        $sql = $this->sql();

        return $this->conn->execute($sql, $this->params);
    }
}