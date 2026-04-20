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

    public function testGetter()
    {
        $app = createApp();

        $app->config->test = 'test';

        $this->assertEquals('test', $app->config->test);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $runtimePath = __DIR__ . '/../runtime';
        if (is_dir($runtimePath)) {
            $this->removeDirectory($runtimePath);
        }
    }

    private function removeDirectory(string $path): void
    {
        $files = array_diff(scandir($path), ['.', '..']);
        foreach ($files as $file) {
            $fullPath = $path . DIRECTORY_SEPARATOR . $file;
            is_dir($fullPath) ? $this->removeDirectory($fullPath) : unlink($fullPath);
        }
        rmdir($path);
    }
}