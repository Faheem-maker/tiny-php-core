<?php

use framework\tests\TestApplication;
use PHPUnit\Framework\TestCase;

class PathTest extends TestCase
{
    public function testResolve()
    {
        $app = createApp();

        define('DS', DIRECTORY_SEPARATOR);

        $this->assertEquals(realpath(__DIR__ . '/../'), $app->path->resolve('@root'));
        $this->assertEquals(realpath(__DIR__ . '/../'), $app->path->resolve('@root/'));
        $this->assertEquals(realpath(__DIR__ . '/../runtime'), $app->path->resolve('@runtime/'));
        $this->assertEquals(realpath(__DIR__ . '/../runtime/logs'), $app->path->resolve('@runtime/logs'));
        $this->assertEquals(realpath(__DIR__ . '/../Unit'), $app->path->resolve('/Unit'));
        $this->assertEquals(realpath(__DIR__ . '/../Unit'), $app->path->resolve('/Unit/'));
        $this->assertEquals('runtime' . DS . 'logs' . DS . 'assets', $app->path->resolve('runtime//logs/assets/'));
    }

    public function testResolveWithDefault()
    {
        $app = createApp();

        $this->assertEquals(realpath(__DIR__ . '/../Unit'), $app->path->resolveWithDefault('Unit', '@root'));
        $this->assertEquals(realpath(__DIR__ . '/../runtime/logs'), $app->path->resolveWithDefault('@runtime/logs', '@root'));
    }

    public function testReplaceDots()
    {
        $app = createApp();

        $this->assertEquals('views/home/index', $app->path->replaceDots('views.home.index'));
        $this->assertEquals('views/home/index.html.php', $app->path->replaceDots('views.home.index\.html\.php'));
    }

    public function testRoot()
    {
        $app = createApp();

        $this->assertEquals(realpath(__DIR__ . '/../'), $app->path->root());
        $this->assertEquals(realpath(__DIR__ . '/../runtime'), $app->path->root('runtime'));
    }
}