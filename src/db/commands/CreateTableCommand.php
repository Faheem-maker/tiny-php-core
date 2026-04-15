<?php

namespace framework\db\commands;

use framework\db\drivers\BaseDriver;
use framework\db\traits\HasTable;

class CreateTableCommand extends BaseCommand
{
    /**
     * @var ColumnDefinition[]
     */
    protected array $columns = [];

    use HasTable;

    public function __construct(BaseDriver $driver, ?string $table = null)
    {
        $this->table = $table;
        parent::__construct($driver);
    }

    /**
     * Define a numeric column.
     * 
     * @param string $name
     * @return ColumnDefinition
     */
    public function number(string $name): ColumnDefinition
    {
        $column = new ColumnDefinition($name, 'number');
        $this->columns[] = $column;
        return $column;
    }

    /**
     * Define an integer column.
     * 
     * @param string $name
     * @return ColumnDefinition
     */
    public function integer(string $name): ColumnDefinition
    {
        $column = new ColumnDefinition($name, 'integer');
        $this->columns[] = $column;
        return $column;
    }

    /**
     * Define a decimal column.
     * 
     * @param string $name
     * @param int $precision
     * @param int $scale
     * @return ColumnDefinition
     */
    public function decimal(string $name, int $precision = 8, int $scale = 2): ColumnDefinition
    {
        $column = new ColumnDefinition($name, 'decimal');
        $column->attribute('precision', $precision);
        $column->attribute('scale', $scale);
        $this->columns[] = $column;
        return $column;
    }

    /**
     * Compiles the CREATE TABLE command into SQL.
     * 
     * @return string
     */
    public function compile(): string
    {
        $columnData = array_map(fn(ColumnDefinition $col) => $col->toArray(), $this->columns);

        return $this->conn->compile('createTable', [
            'table' => $this->table,
            'columns' => $columnData,
        ]);
    }

    /**
     * Executes the CREATE TABLE command.
     * 
     * @return \framework\db\QueryResult
     */
    public function execute()
    {
        $sql = $this->compile();
        return $this->conn->execute($sql);
    }
}
