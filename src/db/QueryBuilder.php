<?php

namespace framework\db;

use framework\db\commands\CreateTableCommand;
use framework\db\commands\DeleteCommand;
use framework\db\commands\InsertCommand;
use framework\db\commands\SelectCommand;
use framework\db\commands\TableExistsCommand;
use framework\db\commands\UpdateCommand;
use framework\db\drivers\BaseDriver;
use framework\Component;

class QueryBuilder extends Component
{
    protected BaseDriver $conn;

    public function __construct(BaseDriver $conn)
    {
        $this->conn = $conn;
    }

    public function init(): void
    {
        $this->conn->connect();
    }

    public function select($cols = '*')
    {
        return new SelectCommand($this->conn, $cols);
    }

    public function createTable($table = null)
    {
        return new CreateTableCommand($this->conn, $table);
    }

    public function update($table, $cols)
    {
        return new UpdateCommand($this->conn, $table, $cols);
    }

    public function insert($table, $cols)
    {
        return (new InsertCommand($this->conn, $table, $cols))
            ->execute();
    }

    public function delete($table)
    {
        return new DeleteCommand($this->conn, $table);
    }

    public function isTable($table)
    {
        return (new TableExistsCommand($this->conn, $table))
            ->execute();
    }

    public function execute(string $sql, array $params = [])
    {
        return $this->conn->execute($sql, $params);
    }

    public function conn()
    {
        return $this->conn;
    }
}