<?php

namespace framework\db\traits;

trait HasJoin
{
    protected $joins = [];

    public function join($table, $condition, $type = 'LEFT', $params = [])
    {
        $this->joins[] = [
            'type' => $type,
            'table' => $table,
            'condition' => $condition,
        ];

        foreach ($params as $key => $value) {
            $this->params[$key] = $value;
        }

        return $this;
    }

    public function leftJoin($table, $condition, $params = [])
    {
        return $this->join($table, $condition, 'LEFT', $params);
    }

    public function innerJoin($table, $condition, $params = [])
    {
        return $this->join($table, $condition, 'INNER', $params);
    }

    public function rightJoin($table, $condition, $params = [])
    {
        return $this->join($table, $condition, 'RIGHT', $params);
    }

    public function fullJoin($table, $condition, $params = [])
    {
        return $this->join($table, $condition, 'FULL', $params);
    }
}