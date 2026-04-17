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

    public function execute()
    {
        $sql = $this->conn->compile('dropTable', [
            'table' => $this->table
        ]);

        return $this->conn->execute($sql);
    }
}
