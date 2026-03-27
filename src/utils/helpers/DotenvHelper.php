<?php

namespace framework\utils\helpers;

class DotenvHelper
{
    /**
     * Load environment variables from a .env file
     *
     * @param string $filePath The path to the .env file
     */
    public static function load(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new \Exception("Dotenv file not found: " . $filePath);
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue; // Skip comments
            }
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            if (!array_key_exists($key, $_ENV)) {
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
    }
}