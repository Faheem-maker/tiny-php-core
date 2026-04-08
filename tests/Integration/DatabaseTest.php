<?php

namespace framework\tests\Integration;

use framework\db\ActiveModel;
use framework\tests\DatabaseTestCase;

use framework\models\attributes\PrimaryKey;

class User extends ActiveModel {
    #[PrimaryKey]
    public int $id;
    public string $name;
    public string $email;
}

class DatabaseTest extends DatabaseTestCase
{
    protected function createSchema(): void
    {
        // SQLite syntax for auto-incrementing primary key
        db()->execute("CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, email TEXT)");
    }

    public function testInsertAndSelect()
    {
        db()->insert('users', ['name' => 'John Doe', 'email' => 'john@example.com']);
        
        $result = db()->select('*')->from('users')->where('name', 'John Doe')->first();
        
        $this->assertEquals('John Doe', $result['name']);
        $this->assertEquals('john@example.com', $result['email']);
    }

    public function testActiveModelSave()
    {
        $user = new User();
        $user->name = 'Jane Doe';
        $user->email = 'jane@example.com';
        $user->save();

        $this->assertNotEmpty($user->id);

        $found = User::find($user->id);
        $this->assertEquals('Jane Doe', $found->name);
    }
}
