<?php

namespace framework\db\commands;

class TransactionCommand extends BaseCommand
{
    protected string $action;

    public function __construct($conn, string $action)
    {
        parent::__construct($conn);
        $this->action = $action;
    }

    public function execute()
    {
        $sql = $this->conn->compile('transaction', [
            'action' => $this->action
        ]);

        return $this->conn->execute($sql);
    }
}
