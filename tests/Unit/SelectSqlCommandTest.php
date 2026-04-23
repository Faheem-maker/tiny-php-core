<?php

namespace framework\tests\Unit;

use framework\tests\MySqlTestCase;

class SelectSqlCommandTest extends MySqlTestCase
{
    /**
     * Test that the SelectCommand generates the correct SQL.
     */
    public function testSelectSqlGeneration()
    {
        // Execute the query builder to get the SQL
        $sql = db()->select('id, username')
            ->from('users')
            ->where('id', 2)
            ->sql();

        // The expected SQL string (generic)
        $expected = "SELECT id, username FROM users WHERE id = :p0";

        // Compare normalized versions
        $this->assertEquals(
            $this->normalizeSql($expected),
            $this->normalizeSql($sql),
            "The generated SQL does not match the expected SQL after normalization."
        );
    }
}
