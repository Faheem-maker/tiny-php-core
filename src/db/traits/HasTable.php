<?php

namespace framework\db\traits;

trait HasTable {
    protected $table;

    public function from($table) {
        $this->table = $table;

        return $this;
    }
};