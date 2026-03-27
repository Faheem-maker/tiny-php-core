<?php

namespace framework\db;

class Relation {
    /**
     * The current (parent) model
     * 
     * Always set this as the current model
     * even for "belongsTo" results
     */
    public ActiveModel $current;

    /**
     * Foreign Model
     * 
     * Fully qualified name of the foreign model
     */
    public string $foreign;

    /**
     * Self Key
     * 
     * The name of the key on "current" model
     */
    public string $self_key;

    /**
     * Foreign key
     * 
     * The name of the foreign key
     */
    public string $foreign_key;

    public bool $many = false;

    public function __construct($current, $foreign, $many = false, $self_key = null, $foreign_key = null)
    {
        $this->current = $current;
        $this->foreign = $foreign;
        $this->many = $many;

        $this->foreign_key = $foreign_key;
        $this->self_key = $self_key;
    }

    public function get() {
        $foreign = $this->foreign;
        $self = $this->self_key;
        $query = $foreign::find($this->current->$self, $this->foreign_key);

        return $this->many ? $query->all() : $query->first();
    }
}