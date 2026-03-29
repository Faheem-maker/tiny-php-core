<?php

namespace framework\tests;

use framework\Application;

class TestApplication extends Application {
    public static function getInstance() {
        if (empty(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function run()
    {
        
    }
}