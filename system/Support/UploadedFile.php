<?php

namespace System\Support;

use System\Support\Facades\FileSystem;

class UploadedFile
{
    public function __construct(
        private array $file
    ){}

    public function getError(): int
    {
        return $this->file['error'];
    }

    public function getFilename(): string
    {
        $names = explode('.', $this->file['name']);
        return implode('.', array_slice($names, 0, -1));
    }

    public function getExtension(): string
    {
        $names = explode('.', $this->file['name']);
        return end($names);
    }

    public function getTempPath(): string
    {
        return $this->file['tmp_name'];
    }

    public function store(string $path, ?string $name = null)
    {
        FileSystem::makeDirectory($path);

        return move_uploaded_file(
            $this->file['tmp_name'],
            $path . '/' . ($name ?? $this->file['name'])
        );
    }
}
