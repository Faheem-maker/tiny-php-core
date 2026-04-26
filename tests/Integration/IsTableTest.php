<?php

namespace framework\tests\Integration;

use framework\tests\DatabaseTestCase;

class IsTableTest extends DatabaseTestCase
{
    protected function createSchema(): void
    {
        // SQLite syntax for auto-incrementing primary key
        db()->execute("CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, email TEXT)");
    }

    public function testIsTable()
    {
        $this->assertTrue(db()->isTable('users'));
        $this->assertFalse(db()->isTable('posts'));
    }
}
