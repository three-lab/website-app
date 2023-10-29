<?php

namespace System\Support\Facades;

use System\Support\FileSystem as SupportFileSystem;

/**
 * @method static void makeDirectory(string $path, int $mode = 0755)
 */
class FileSystem extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SupportFileSystem::class;
    }
}
