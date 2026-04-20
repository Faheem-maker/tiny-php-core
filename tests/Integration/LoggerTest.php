<?php

namespace framework\tests\Integration;

use framework\components\Logger;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Ensure app is created and logger is registered
        if (!function_exists('app')) {
            require_once __DIR__ . '/../helpers.php';
        }
        $app = createApp();
        $app->registerComponent('logger', new Logger());
    }

    public function testLoggerCreatesLogFile()
    {
        $message = "Integration test log message " . uniqid();
        app()->logger->info($message);

        $logFile = app()->path->resolve('@runtime/logs/app-' . date('Y-m-d') . '.log');

        $this->assertFileExists($logFile);
        $content = file_get_contents($logFile);
        $this->assertStringContainsString('INFO', $content);
        $this->assertStringContainsString($message, $content);
    }

    protected function tearDown(): void
    {
        $runtimePath = app()->path->resolve('@runtime');
        if (is_dir($runtimePath)) {
            $this->removeDirectory($runtimePath);
        }
        parent::tearDown();
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
