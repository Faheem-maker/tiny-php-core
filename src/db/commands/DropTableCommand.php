<?php

namespace framework\db\commands;

use framework\db\traits\HasTable;

class DropTableCommand extends BaseCommand
{
    use HasTable;

    public function __construct($conn, $table)
    {
        parent::__construct($conn);
        $this->table = $table;
    }

    public function sql(): string
    {
        return $this->conn->compile('dropTable', [
            'table' => $this->table
        ]);
    }

    public function execute(): \framework\db\QueryResult
    {
        $sql = $this->sql();

        return $this->conn->execute($sql);
    }
}
