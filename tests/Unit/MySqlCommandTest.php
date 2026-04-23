<?php

namespace framework\tests\Unit;

use framework\db\commands\WhereClause;
use framework\tests\MySqlTestCase;

class MySqlCommandTest extends MySqlTestCase
{
    /**
     * Test that the SelectCommand generates the correct SQL.
     */
    public function testSelectSqlGeneration()
    {
        // Execute the query builder to get the SQL
        $sql = db()->select('id, username')
            ->from('users')
            ->leftJoin('orders', 'users.id = orders.user_id')
            ->innerJoin('products', 'products.id = orders.product_id')
            ->where('id', 2)
            ->where(function (WhereClause $where) {
                $where->andWhere('username', 'john')->orWhere('username', 'jane');
            })
            ->orderBy('id', 'desc')
            ->sql();

        // The expected SQL string (generic)
        $expected = "SELECT id, username FROM users LEFT JOIN orders ON users.id = orders.user_id INNER JOIN products ON products.id = orders.product_id WHERE id = :p0 AND (username = :p1 OR username = :p2) ORDER BY id DESC";

        // Compare normalized versions
        $this->assertEquals(
            $this->normalizeSql($expected),
            $this->normalizeSql($sql),
            "The generated SQL does not match the expected SQL after normalization."
        );
    }

    public function testUpdateSqlGeneration()
    {
        // Execute the query builder to get the SQL
        $sql = db()->update('users', [
            'username' => 'new_username',
            'email' => 'new_email',
        ])
            ->where('id', 2)
            ->sql();

        // The expected SQL string (generic)
        $expected = "UPDATE users SET username = :username,email = :email WHERE id = :p0";

        // Compare normalized versions
        $this->assertEquals(
            $this->normalizeSql($expected),
            $this->normalizeSql($sql),
            "The generated SQL does not match the expected SQL after normalization."
        );
    }

    public function testDeleteSqlGeneration()
    {
        // Execute the query builder to get the SQL
        $sql = db()->delete('users')
            ->where('id', 2)
            ->sql();

        // The expected SQL string (generic)
        $expected = "DELETE FROM users WHERE id = :p0";

        // Compare normalized versions
        $this->assertEquals(
            $this->normalizeSql($expected),
            $this->normalizeSql($sql),
            "The generated SQL does not match the expected SQL after normalization."
        );
    }

    public function testInsertSqlGeneration()
    {
        // Execute the query builder to get the SQL
        $sql = db()->insert('users', [
            'username' => 'new_username',
            'email' => 'new_email',
        ], false)
            ->sql();

        // The expected SQL string (generic)
        $expected = "INSERT INTO users (username,email) VALUES (:username,:email)";

        // Compare normalized versions
        $this->assertEquals(
            $this->normalizeSql($expected),
            $this->normalizeSql($sql),
            "The generated SQL does not match the expected SQL after normalization."
        );
    }

    public function testCreateTableSqlGeneration()
    {
        // Execute the query builder to get the SQL
        $tableCmd = db()->createTable('users');
        $tableCmd->id();
        $tableCmd->string('username', 255);
        $tableCmd->string('email', 255)->nullable()->default('sample@test.com')->unique();
        $sql = $tableCmd->sql();

        // The expected SQL string (generic)
        $expected = "CREATE TABLE `users` (`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,`username` VARCHAR(255) NOT NULL,`email` VARCHAR(255) NULL DEFAULT 'sample@test.com' UNIQUE)";

        // Compare normalized versions
        $this->assertEquals(
            $this->normalizeSql($expected),
            $this->normalizeSql($sql),
            "The generated SQL does not match the expected SQL after normalization."
        );
    }

    public function testTableExistsSqlGeneration()
    {
        $command = new \framework\db\commands\TableExistsCommand(db()->conn(), 'users');
        $sql = $command->sql();

        $expected = "SHOW TABLES LIKE 'users'";

        $this->assertEquals(
            $this->normalizeSql($expected),
            $this->normalizeSql($sql),
            "The generated SQL for table existence check does not match the expected SQL."
        );
    }

    public function testDropTableSqlGeneration()
    {
        $command = new \framework\db\commands\DropTableCommand(db()->conn(), 'users');
        $sql = $command->sql();

        $expected = "DROP TABLE `users`";

        $this->assertEquals(
            $this->normalizeSql($expected),
            $this->normalizeSql($sql),
            "The generated SQL for dropping a table does not match the expected SQL."
        );
    }

    public function testTransactionSqlGeneration()
    {
        // Begin
        $sql = db()->beginTransaction(false)->sql();
        $this->assertEquals($this->normalizeSql("START TRANSACTION"), $this->normalizeSql($sql));

        // Commit
        $sql = db()->commit(false)->sql();
        $this->assertEquals($this->normalizeSql("COMMIT"), $this->normalizeSql($sql));

        // Rollback
        $sql = db()->rollback(false)->sql();
        $this->assertEquals($this->normalizeSql("ROLLBACK"), $this->normalizeSql($sql));
    }
}
