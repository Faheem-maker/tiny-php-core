<?php

use framework\components\PathManager;
use framework\tests\TestApplication;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    public function testAppCreation()
    {
        $app = createApp();

        $this->assertInstanceOf(TestApplication::class, $app);
    }

    public function testComponentRegistration() {
        $app = createApp();

        $app->registerComponent('path', new PathManager());

        $this->assertInstanceOf(PathManager::class, $app->path);
    }

    public function testLazyComponent() {
        $app = createApp();

        $app->registerComponent('path', PathManager::class);

        $this->assertInstanceOf(PathManager::class, $app->path);
    }

    public function testFactoryComponent() {
        $app = createApp();

        $app->registerComponent('path', function() {
            return new PathManager();
        });

        $this->assertInstanceOf(PathManager::class, $app->path);
    }
}