<?php

namespace framework\components;

use framework\Component;
use framework\contracts\components\FileSystem as FileSystemContract;

class FileSystem extends Component implements FileSystemContract
{
    public function move(string $source, string $destination): bool
    {
        $directory = dirname($destination);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        return rename($source, $destination);
    }

    public function exists(string $path): bool
    {
        return file_exists($path);
    }
}