<?php

namespace framework\utils;

class FileLogger
{
    private string $logPath;
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * @param string $directory The directory where logs will be stored.
     */
    public function __construct(string $directory)
    {
        // Ensure the directory exists and is writable
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        
        $this->logPath = rtrim($directory, DIRECTORY_SEPARATOR);
    }

    /**
     * Core logging method
     * * @param string $level The severity level (INFO, ERROR, DEBUG, etc.)
     * @param string $message The content of the log
     */
    public function log(string $level, string $message): void
    {
        $timestamp = date(self::DATE_FORMAT);
        $fileName = 'app-' . date('Y-m-d') . '.log';
        $fullPath = $this->logPath . DIRECTORY_SEPARATOR . $fileName;

        // Format: [2026-03-22 14:30:05] [ERROR] User login failed.
        $formattedMessage = sprintf("[%s] [%s] %s%s", $timestamp, strtoupper($level), $message, PHP_EOL);

        // FILE_APPEND ensures we don't overwrite; LOCK_EX prevents race conditions
        file_put_contents($fullPath, $formattedMessage, FILE_APPEND | LOCK_EX);
    }
}