<?php

namespace App\Infrastructure\Overview;

use App\Infrastructure\Overview\Sorting\Sorting;

class Overview implements \JsonSerializable
{
    private array $items = [];

    private function __construct(
        private readonly Pagination $pagination,
        private readonly int $total,
        private readonly ?Sorting $sorting = null)
    {
    }

    public static function empty(
        Pagination $pagination,
        Sorting $sorting = null,
        int $total = 0): Overview
    {
        return new self($pagination, $total, $sorting);
    }

    public function jsonSerialize(): array
    {
        return [
            'pagination' => $this->getPagination(),
            'sorting' => $this->getSorting(),
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

    public function getSorting(): ?Sorting
    {
        return $this->sorting;
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }
}
