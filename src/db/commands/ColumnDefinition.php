<?php

namespace framework\db\commands;

class ColumnDefinition
{
    protected string $name;
    protected string $type;
    protected bool $isNullable = false;
    protected $default = null;
    protected array $attributes = [];

    public function __construct(string $name, string $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    public function nullable(bool $value = true): self
    {
        $this->isNullable = $value;
        return $this;
    }

    public function default($value): self
    {
        $this->default = $value;
        return $this;
    }

    public function attribute(string $key, $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'nullable' => $this->isNullable,
            'default' => $this->default,
            'attributes' => $this->attributes,
        ];
    }
}
