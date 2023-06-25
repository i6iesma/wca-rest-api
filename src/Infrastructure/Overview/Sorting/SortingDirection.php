<?php

namespace App\Infrastructure\Overview\Sorting;

enum SortingDirection: string
{
    case ASCENDING = 'ascending';
    case DESCENDING = 'descending';

    public function toSql(): string
    {
        return SortingDirection::DESCENDING === $this ? 'DESC' : 'ASC';
    }

    public function equals(SortingDirection $a): bool
    {
        return $a === $this;
    }
}
