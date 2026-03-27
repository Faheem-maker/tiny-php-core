<?php

namespace framework\db;

abstract class QueryResult
{
    public abstract function fetch();
    public abstract function fetchAll();
    public abstract function rowCount();
}