<?php

namespace System\Support;

class FileSystem
{
    public function makeDirectory(string $path, int $mode = 0755): void
    {
        if(!is_dir($path))
            mkdir($path, $mode, true);
    }
}
