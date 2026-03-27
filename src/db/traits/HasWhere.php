<?php

namespace framework\db\traits;

use framework\db\commands\WhereClause;


trait HasWhere
{
    protected $where = [];

    public function where($condition, $params = [], $operator = 'AND')
    {
        if (\is_callable($condition)) {
            $whereCommand = new WhereClause($this->params);
            $condition($whereCommand);

            $this->where[] = [
                'type' => 'group',
                'operator' => $operator,
                'conditions' => $whereCommand->getWhere(),
            ];

            return $this;
        }

        if (!\is_array($params)) {
            $cnt = \count($this->params);
            $condition = "$condition = :p$cnt";
            $params = [
                ":p$cnt" => $params,
            ];
        }
        $this->where[] = [
            'type' => 'condition',
            'operator' => $operator,
            'condition' => $condition,
        ];

        foreach ($params as $key => $value) {
            $this->params[$key] = $value;
        }

        return $this;
    }

    public function andWhere($condition, $params = [])
    {
        return $this->where($condition, $params, 'AND');
    }

    public function orWhere($condition, $params = [])
    {
        return $this->where($condition, $params, 'OR');
    }
}