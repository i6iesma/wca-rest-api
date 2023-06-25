<?php

namespace App\Domain\Person;

use App\Domain\Country\Iso2Code;
use App\Infrastructure\Overview\Item;

readonly class Person implements Item
{
    private function __construct(
        private string $id,
        private string $name,
        private Iso2Code $country,
        private array $competitions,
        private Rank $single,
        private Rank $average,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'country' => $this->country,
            'numberOfCompetitions' => count($this->competitions),
            'competitions' => $this->competitions,
            'rank' => [
                'single' => $this->single,
                'average' => $this->average,
            ],
        ];
    }
}
