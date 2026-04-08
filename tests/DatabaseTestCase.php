<?php

namespace framework\tests;

use framework\db\drivers\SqliteDriver;
use framework\db\QueryBuilder;
use PHPUnit\Framework\TestCase;

abstract class DatabaseTestCase extends TestCase
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

        // Register SQLite in-memory database
        $driver = new SqliteDriver(['database' => ':memory:']);
        $db = new QueryBuilder($driver);
        
        // Register the db component to the application
        app()->registerComponent('db', $db);
        
        // Initialize the database connection
        app()->db->init();

        // Set up the schema for the test
        $this->createSchema();
    }

    /**
     * Override this method in your test class to define the database schema.
     * Example:
     * db()->execute("CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, email TEXT)");
     */
    protected function createSchema(): void
    {
    }

    protected function tearDown(): void
    {
        if (app()->has('db')) {
            app()->db->conn()->disconnect();
        }
        parent::tearDown();
    }
}
