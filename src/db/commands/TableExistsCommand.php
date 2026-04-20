<?php

namespace framework\db\commands;

use framework\db\traits\HasTable;

class TableExistsCommand extends BaseCommand
{
    use HasTable;

    public function __construct($conn, $table)
    {
        parent::__construct($conn);
        $this->table = $table;
    }

    public function execute(): bool
    {
        $sql = $this->conn->compile('tableExists', [
            'table' => $this->table
        ]);

        return (bool) $this->conn->execute($sql)->fetch();
    }
}
