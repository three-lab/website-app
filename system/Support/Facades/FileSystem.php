<?php

namespace System\Support\Facades;

use System\Support\FileSystem as SupportFileSystem;

/**
 * @method static void makeDirectory(string $path, int $mode = 0755)
 * @method static void put(string $path, string $content)
 * @method static mixed get(string $path, bool $isJson = false)
 * @method static void remove(string $path)
 */
class FileSystem extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SupportFileSystem::class;
    }
}
