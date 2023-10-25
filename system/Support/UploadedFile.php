<?php

namespace System\Support;

class UploadedFile
{
    public function __construct(
        private array $file
    ){}

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

    public function store(string $path, ?string $name = null)
    {
        return move_uploaded_file(
            $this->file['tmp_name'],
            $path . '/' . ($name ?? $this->file['name'])
        );
    }
}
