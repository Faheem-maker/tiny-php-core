<?php

namespace framework\utils\helpers;

/**
 * DirectoryHelper provides utility functions for directory operations
 */
class DirectoryHelper
{
    public static function listFiles(string $dir, string $exclude = ''): array
    {
        $files = [];
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file != '.' && $file != '..' && $file != $exclude && is_file("$dir/$file")) {
                        $files[] = $file;
                    }
                }
                closedir($dh);
            }
        }
        return $files;
    }
}