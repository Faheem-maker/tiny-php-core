<?php

namespace framework\tests;

use framework\db\drivers\MySqlDriver;
use framework\db\drivers\SqliteDriver;
use framework\db\QueryBuilder;
use PHPUnit\Framework\TestCase;

abstract class MySqlTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Ensure helpers are loaded
        if (!function_exists('createApp')) {
            require_once __DIR__ . '/helpers.php';
        }

        // Initialize application if not already done
        createApp();

        // Register a mock database
        $driver = new MySqlDriver([
            'database' => 'faked',
            'host' => 'localhost',
            'username' => 'root',
            'password' => ''
        ]);
        $db = new QueryBuilder($driver);

        // Register the db component to the application
        app()->registerComponent('db', $db);

        // Initialize the database connection
        // app()->db->init();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * A simple and straightforward method to normalize SQL for comparison.
     * It handles case sensitivity, extra whitespace, and common query builder artifacts.
     */
    protected function normalizeSql(string $sql): string
    {
        // Convert to lowercase for case-insensitive comparison
        $sql = strtolower($sql);

        // Collapse all whitespace (spaces, tabs, newlines) into a single space
        $sql = preg_replace('/\s+/', ' ', $sql);

        // Remove the "WHERE 1 = 1" boilerplate often added by query builders
        $sql = str_replace('where 1 = 1 and', 'where', $sql);
        $sql = str_replace('where 1 = 1', 'where', $sql);

        // Remove trailing spaces after commas
        $sql = str_replace(', ', ',', $sql);

        // Remove spaces after opening brackets and before closing brackets
        $sql = str_replace('( ', '(', $sql);
        $sql = str_replace(' )', ')', $sql);

        return trim($sql);


    }
}
