<?php

namespace App\Domain;

use App\Infrastructure\Overview\Pagination;
use League\Flysystem\Filesystem;

class ApiFileWriter
{
    public function __construct(
        private readonly Filesystem $filesystem
    ) {
    }

    public function write(
        string $fileName,
        string $contents,
    ): void {
        $this->filesystem->write(
            sprintf('/api/%s.json', trim($fileName, '/')),
            $contents
        );
    }

    public function writeWithPagination(
        string $fileName,
        Pagination $pagination,
        string $contents,
    ): void {
        $this->filesystem->write(
            sprintf('/api/%s-page-%s.json', trim($fileName, '/'), $pagination->getPageNumber()),
            $contents
        );
    }
}
