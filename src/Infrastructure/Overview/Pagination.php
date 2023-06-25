<?php

namespace App\Infrastructure\Overview;

readonly class Pagination implements \JsonSerializable
{
    private function __construct(
        private int $offset = 0,
        private int $limit = 1000)
    {
        if ($this->limit < 1) {
            throw new \InvalidArgumentException('Invalid limit: '.$this->limit);
        }
    }

    public static function fromPageNumberAndSize(int $pageNumber = 1): self
    {
        $pageSize = 1000;

        return new self(($pageNumber - 1) * $pageSize, $pageSize);
    }

    public static function fromOffsetAndLimit(int $offset = 0): self
    {
        return new self($offset, 1000);
    }

    public static function default(): Pagination
    {
        return new self();
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getPageNumber(): int
    {
        return (int) \floor($this->offset / $this->limit) + 1;
    }

    public function next(): Pagination
    {
        return new self($this->offset + $this->limit, $this->limit);
    }

    public function getPageSize(): int
    {
        return $this->limit;
    }

    public function jsonSerialize(): array
    {
        return [
            'page' => $this->getPageNumber(),
            'size' => $this->getPageSize(),
        ];
    }
}
