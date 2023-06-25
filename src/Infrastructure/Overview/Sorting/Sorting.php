<?php

namespace App\Infrastructure\Overview\Sorting;

readonly class Sorting implements \JsonSerializable
{
    private function __construct(
        private string $sortableFieldName,
        private SortingDirection $sortingDirection)
    {
    }

    public static function with(string $fieldName, SortingDirection $sortingDirection): self
    {
        return new self($fieldName, $sortingDirection);
    }

    public function getSortableFieldName(): string
    {
        return $this->sortableFieldName;
    }

    public function getSortingDirection(): SortingDirection
    {
        return $this->sortingDirection;
    }

    public function jsonSerialize(): array
    {
        return [
            'field' => $this->sortableFieldName,
            'direction' => $this->sortingDirection,
        ];
    }
}
