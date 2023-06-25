<?php

namespace App\Infrastructure\Overview;

readonly class Pagination implements \JsonSerializable
{
    private function __construct(
        private int $offset = 0,
        private int $limit = 100)
    {
        if ($this->limit < 1) {
            throw new \InvalidArgumentException('Invalid limit: '.$this->limit);
        }
    }

    public static function fromPageNumberAndSize(int $pageNumber = 1, int $pageSize = 100): self
    {
        return new self(($pageNumber - 1) * $pageSize, $pageSize);
    }

    public static function fromOffsetAndLimit(int $offset = 0, int $limit = 100): self
    {
        return new self($offset, $limit);
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

    public function toSqlLimit(): string
    {
        return ' LIMIT '.$this->getLimit().' OFFSET '.$this->getOffset();
    }
}
