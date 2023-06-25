<?php

namespace App\Infrastructure\Overview\Sorting;

readonly class Sorting implements \JsonSerializable
{
    private function __construct(
        private SortableField $sortableFieldName,
        private SortingDirection $sortingDirection)
    {
    }

    public static function with(SortableField $fieldName, SortingDirection $sortingDirection): self
    {
        return new self($fieldName, $sortingDirection);
    }

    public function getSortableFieldName(): string
    {
        return $this->sortableFieldName->value;
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
