<?php

namespace framework\tests\Integration;

use framework\components\FileSystem;
use PHPUnit\Framework\TestCase;

class FileSystemTest extends TestCase
{
    public function setUp(): void
    {
        mkdir(__DIR__ . '/tmp', 0777, true);
        parent::setUp();
    }

    public function testMoveFile()
    {
        $fileSystem = new FileSystem();

        $source = __DIR__ . '/tmp/test.txt';
        $destination = __DIR__ . '/tmp/test_moved.txt';

        file_put_contents($source, 'test');

        $fileSystem->move($source, $destination);

        $this->assertFileExists($destination);
        $this->assertFileDoesNotExist($source);

        unlink($destination);
    }

    public function testCreateDir()
    {
        $fileSystem = new FileSystem();

        $source = __DIR__ . '/tmp/test.txt';
        $destination = __DIR__ . '/tmp/path/test_moved.txt';

        file_put_contents($source, 'test');

        $fileSystem->move($source, $destination);

        $this->assertFileExists($destination);
        $this->assertFileDoesNotExist($source);

        unlink($destination);
    }

    public function testExists()
    {
        $fileSystem = new FileSystem();

        $this->assertTrue($fileSystem->exists(__DIR__));
        $this->assertFalse($fileSystem->exists(__DIR__ . '/nonexistent'));
    }

    public function tearDown(): void
    {
        $this->delTree(__DIR__ . '/tmp');
        parent::tearDown();
    }

    private function delTree($dir)
    {
        $files = array_diff(scandir($dir), array('.', '..'));

        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file");
        }

        return rmdir($dir);
    }
}