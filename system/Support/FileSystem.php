<?php

namespace System\Support;

class FileSystem
{
    public function makeDirectory(string $path, int $mode = 0755): void
    {
        if(!is_dir($path))
            mkdir($path, $mode, true);
    }

    public function put(string $path, string $content): void
    {
        $this->makeDirectory(dirname($path));
        file_put_contents($path, $content);
    }

    public function get(string $path, bool $isJson = false): null|string|array
    {
        if(!is_file($path)) return null;

        $content = file_get_contents($path);
        if(!$content) return null;

        return $isJson ? json_decode($content, true) : $content;
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
