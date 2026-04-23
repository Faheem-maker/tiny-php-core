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
     * Define an auto-incrementing primary key ID column.
     * 
     * @param string $name
     * @return ColumnDefinition
     */
    public function id(string $name = 'id'): ColumnDefinition
    {
        return $this->integer($name)
            ->attribute('primary', true)
            ->attribute('autoIncrement', true);
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
     * Define a string column.
     * 
     * @param string $name
     * @param int $length
     * @return ColumnDefinition
     */
    public function string(string $name, int $length = 255): ColumnDefinition
    {
        $column = new ColumnDefinition($name, 'string');
        $column->attribute('length', $length);
        $this->columns[] = $column;
        return $column;
    }

    /**
     * Define a char column.
     * 
     * @param string $name
     * @param int $length
     * @return ColumnDefinition
     */
    public function char(string $name, int $length = 255): ColumnDefinition
    {
        $column = new ColumnDefinition($name, 'char');
        $column->attribute('length', $length);
        $this->columns[] = $column;
        return $column;
    }

    /**
     * Define a date column.
     * 
     * @param string $name
     * @return ColumnDefinition
     */
    public function date(string $name): ColumnDefinition
    {
        $column = new ColumnDefinition($name, 'date');
        $this->columns[] = $column;
        return $column;
    }

    /**
     * Define a boolean column.
     * 
     * @param string $name
     * @return ColumnDefinition
     */
    public function boolean(string $name): ColumnDefinition
    {
        $column = new ColumnDefinition($name, 'boolean');
        $this->columns[] = $column;
        return $column;
    }

    /**
     * Define a date-time column.
     * 
     * @param string $name
     * @return ColumnDefinition
     */
    public function dateTime(string $name): ColumnDefinition
    {
        $column = new ColumnDefinition($name, 'dateTime');
        $this->columns[] = $column;
        return $column;
    }

    /**
     * Define a time column.
     * 
     * @param string $name
     * @return ColumnDefinition
     */
    public function time(string $name): ColumnDefinition
    {
        $column = new ColumnDefinition($name, 'time');
        $this->columns[] = $column;
        return $column;
    }

    /**
     * Define a timestamp column.
     * 
     * @param string $name
     * @return ColumnDefinition
     */
    public function timestamp(string $name): ColumnDefinition
    {
        $column = new ColumnDefinition($name, 'timestamp');
        $this->columns[] = $column;
        return $column;
    }

    /**
     * Define created_at and updated_at timestamp columns.
     * 
     * @return void
     */
    public function timestamps(): void
    {
        $this->timestamp('created_at')->nullable();
        $this->timestamp('updated_at')->nullable();
    }

    /**
     * Define a foreign ID column for a given model.
     * 
     * @param string $model
     * @param string|null $column
     * @return ColumnDefinition
     */
    public function foreignIdFor(string $model, ?string $column = null): ColumnDefinition
    {
        if (empty($column)) {
            $column = $model::table() . '_id';
        }

        return $this->integer($column);
    }

    /**
     * Alias for foreignIdFor.
     * 
     * @param string $model
     * @param string|null $column
     * @return ColumnDefinition
     */
    public function foreignKeyFor(string $model, ?string $column = null): ColumnDefinition
    {
        return $this->foreignIdFor($model, $column);
    }

    /**
     * Compiles the CREATE TABLE command into SQL.
     * 
     * @return string
     */
    public function sql(): string
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
    public function execute(): \framework\db\QueryResult
    {
        $sql = $this->sql();
        return $this->conn->execute($sql);
    }
}
