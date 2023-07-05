<?php

namespace App\Tests;

use App\Domain\ApiFileWriter;
use App\Infrastructure\Overview\Pagination;
use App\Infrastructure\Serialization\Json;

class SpyApiFileWriter extends ApiFileWriter
{
    private array $writes = [];

    public function write(string $fileName, string $contents): void
    {
        $this->writes[$fileName] = Json::decode($contents);
    }

    public function writeWithPagination(string $fileName, Pagination $pagination, string $contents): void
    {
        $this->writes[$fileName.'-'.$pagination->getPageNumber()] = Json::decode($contents);
    }

    public function getWrites(): array
    {
        return $this->writes;
    }
}
