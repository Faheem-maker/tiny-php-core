<?php

namespace framework\db\drivers;

use Exception;
use framework\db\QueryResult;
use PDO;

class SqliteDriver extends BaseDriver
{
    protected $conn;

    protected function getDsn(): string
    {
        if (empty($this->config['database']) || $this->config['database'] === ':memory:') {
            return "sqlite::memory:";
        }

        return "sqlite:{$this->config['database']}";
    }

    public function connect(): void
    {
        $this->conn = new \PDO($this->getDsn());
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function disconnect(): void
    {
        $this->conn = null;
    }

    public function compile(string $type, array $components): string
    {
        switch ($type) {
            case 'select':
                return $this->compileSelect($components);
            case 'update':
                return $this->compileUpdate($components);
            case 'insert':
                return $this->compileInsert($components);
            case 'delete':
                return $this->compileDelete($components);
            default:
                throw new Exception("Unsupported query type: {$type}");
        }
    }

    protected function compileSelect(array $components): string
    {
        $cols = implode(', ', $components['columns'] ?? ['*']);
        $query = "SELECT {$cols} FROM {$components['table']}";

        if (!empty($components['joins'])) {
            foreach ($components['joins'] as $join) {
                $query .= " {$join['type']} JOIN {$join['table']} ON {$join['condition']}";
            }
        }

        $query = $this->compileWhere($query, $components['where']);

        return $query;
    }

    protected function compileDelete(array $components): string
    {
        $query = "DELETE FROM {$components['table']}";

        $query = $this->compileWhere($query, $components['where']);

        return $query;
    }

    protected function compileInsert(array $components)
    {
        $cols = implode(', ', array_keys($components['columns']));
        $placeholders = implode(', ', array_map(fn($key) => ":$key", array_keys($components['columns'])));

        return "INSERT INTO {$components['table']} ({$cols}) VALUES ({$placeholders})";
    }

    protected function compileUpdate(array $components)
    {
        $query = 'UPDATE ' . $components['table'] . ' SET ';

        foreach ($components['columns'] as $key => $_) {
            $query .= "$key = :$key,";
        }

        $query = \rtrim($query, ',');

        $query = $this->compileWhere($query, $components['where']);

        return $query;
    }

    protected function compileWhere(string $query, array $where, bool $root = true)
    {
        if (empty($where)) {
            return $query;
        }

        if ($root) {
            $query .= ' WHERE 1 = 1';
        }

        foreach ($where as $i => $cond) {
            $operator = ($root || $i > 0) ? " {$cond['operator']}" : "";

            if (isset($cond['type']) && $cond['type'] === 'group') {
                $query .= "{$operator} (";
                $query = $this->compileWhere($query, $cond['conditions'], false);
                $query .= ")";
            } else {
                $query .= "{$operator} {$cond['condition']}";
            }
        }

        return $query;
    }

    public function execute(string $sql, array $params = []): QueryResult
    {
        foreach ($params as $key => $value) {
            if ($value instanceof \DateTime) {
                $params[$key] = $value->format('Y-m-d H:i:s');
            }
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return new SqliteResult($stmt);
    }

    public function lastInsertId(): int
    {
        return (int)$this->conn->lastInsertId();
    }
}
