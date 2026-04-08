<?php

namespace framework\db\drivers;

use framework\db\QueryResult;

class SqliteResult extends QueryResult {
    protected $stmt;

    public function __construct($stmt)
    {
        $this->stmt = $stmt;
    }

    public function fetch()
    {
        return $this->stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function fetchAll()
    {
        return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function rowCount()
    {
        return $this->stmt->rowCount();
    }
}
