<?php

namespace framework\db\commands;

use framework\db\traits\HasJoin;
use \framework\db\traits\HasTable;
use \framework\db\drivers\BaseDriver;
use framework\db\traits\HasWhere;

class SelectCommand extends BaseCommand
{
    protected array $cols;
    protected array $params = [];
    public $transform = null;

    use HasTable;
    use HasWhere;
    use HasJoin;

    public function __construct(BaseDriver $driver, string|array $cols)
    {
        if (\is_string($cols)) {
            $cols = explode(',', $cols);
        }

        $this->cols = $cols;
        parent::__construct($driver);
    }

    public function compile()
    {
        return $this->conn->compile('select', [
            'table' => $this->table,
            'columns' => $this->cols,
            'condition' => $this->where,
            'where' => $this->where,
            'joins' => $this->joins,
        ]);
    }

    protected function transform($data) {
        if (is_callable($this->transform)) {
            return array_map($this->transform, $data);
        }

        return $data;

    }

    public function all()
    {
        $sql = $this->compile();

        return $this->transform($this->conn->execute($sql, $this->params)->fetchAll());
    }

    public function first()
    {
        $sql = $this->compile();
        return $this->transform($this->conn->execute($sql, $this->params)->fetch());
    }

    public function count()
    {
        $sql = $this->compile();
        return $this->conn->execute($sql, $this->params)->rowCount();
    }
}