<?php

namespace System\Support;

class FileSystem
{
    public function makeDirectory(string $path, int $mode = 0755): void
    {
        if(!is_dir($path))
            mkdir($path, $mode, true);
    }

    public function remove(string $path): void
    {
        if(is_file($path)) unlink($path);
        if(is_dir($path)) {
            array_map(fn($file) => @unlink("$path/$file"), scandir($path));
            rmdir($path);
        }
    }
}
