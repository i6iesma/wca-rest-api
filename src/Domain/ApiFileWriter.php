<?php

namespace App\Domain;

use League\Flysystem\Filesystem;

readonly class ApiFileWriter
{
    public function __construct(
        private Filesystem $filesystem
    ) {
    }

    public function write(
        string $fileName,
        string $contents,
    ): void {
        $this->filesystem->write('/api/'.trim($fileName, '/'), $contents);
    }
}
