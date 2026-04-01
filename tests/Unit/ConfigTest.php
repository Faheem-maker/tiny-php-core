<?php

use framework\tests\TestApplication;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testDefaultConfig()
    {
        createApp();

        $this->assertEquals('tests', config('TEST_KEY'));
    }

    public function testDotAccess()
    {
        createApp();

        $this->assertEquals(realpath(__DIR__ . '/..'), config('paths.base_dir'));
    }

    public function testUpdate()
    {
        $app = createApp();

        $app->config->set('TEST_KEY', 'new_tests');

        $this->assertEquals('new_tests', config('TEST_KEY'));
    }

    public function testNestedUpdate()
    {
        $app = createApp();

        $app->config->set('paths.base_dir', 'new_tests');

        $this->assertEquals('new_tests', config('paths.base_dir'));
    }
}