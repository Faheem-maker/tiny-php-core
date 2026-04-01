<?php

use framework\tests\TestApplication;
use PHPUnit\Framework\TestCase;

class PathTest extends TestCase
{
    public function testResolve()
    {
        $app = createApp();

        $this->assertEquals(realpath(__DIR__ . '/../'), $app->path->resolve('@root'));
        $this->assertEquals(realpath(__DIR__ . '/../'), $app->path->resolve('@root/'));
        $this->assertEquals(realpath(__DIR__ . '/../runtime'), $app->path->resolve('@runtime/'));
        $this->assertEquals(realpath(__DIR__ . '/../runtime/logs'), $app->path->resolve('@runtime/logs'));
        $this->assertEquals(realpath(__DIR__ . '/../Unit'), $app->path->resolve('/Unit'));
        $this->assertEquals(realpath(__DIR__ . '/../Unit'), $app->path->resolve('/Unit/'));
        $this->assertEquals('runtime/logs/assets', $app->path->resolve('runtime//logs/assets/'));
    }
}