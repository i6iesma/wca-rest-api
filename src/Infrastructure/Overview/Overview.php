<?php

namespace App\Infrastructure\Overview;

class Overview implements \JsonSerializable
{
    private array $items = [];

    private function __construct(
        private readonly Pagination $pagination,
        private readonly int $total)
    {
    }

    public static function empty(
        Pagination $pagination,
        int $total = 0): Overview
    {
        return new self($pagination, $total);
    }

    public function jsonSerialize(): array
    {
        return [
            'pagination' => $this->getPagination(),
            'total' => $this->getTotal(),
            'items' => $this->items,
        ];
    }

    public function addItem(Item $item): void
    {
        $this->items[] = $item;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getPagination(): Pagination
    {
        return $this->pagination;
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }
}
