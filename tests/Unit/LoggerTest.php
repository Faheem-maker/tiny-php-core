<?php

namespace framework\tests\Unit;

use framework\utils\FileLogger;
use framework\components\Logger;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{
    protected $fileLoggerMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Initialize application and register required components
        $app = createApp();

        // Mock the underlying FileLogger
        $fileLoggerMock = $this->createMock(FileLogger::class);

        // Create the Logger component and inject the mock FileLogger
        $loggerComponent = new Logger();
        $loggerComponent->logger = $fileLoggerMock;

        // Register the Logger component as 'logger' in the app
        $app->registerComponent('logger', $loggerComponent);

        // We'll keep the mock in a property to use in assertions
        $this->fileLoggerMock = $fileLoggerMock;
    }

    public function testInfoLog()
    {
        $message = "Test info message";

        // Expect 'log' to be called on FileLogger mock
        $this->fileLoggerMock->expects($this->once())
            ->method('log')
            ->with('INFO', $message);

        // Using the helper function as requested
        logs()->info($message);
    }

    public function testErrorLog()
    {
        $message = "Test error message";

        $this->fileLoggerMock->expects($this->once())
            ->method('log')
            ->with('ERROR', $message);

        logs()->error($message);
    }

    public function testDebugLog()
    {
        $message = "Test debug message";

        $this->fileLoggerMock->expects($this->once())
            ->method('log')
            ->with('DEBUG', $message);

        logs()->debug($message);
    }
}
