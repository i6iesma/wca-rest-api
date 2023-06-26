<?php

namespace App\Domain\Person;

use App\Domain\Country\Iso2Code;
use App\Domain\Rank\Rank;
use App\Domain\Rank\RankType;
use App\Infrastructure\Overview\Item;

readonly class Person implements Item
{
    private function __construct(
        private string $id,
        private string $name,
        private Iso2Code $country,
        private array $competitionIds,
        private array $ranks,
    ) {
    }

    public static function fromState(
        string $id,
        string $name,
        Iso2Code $country,
        array $competitionIds,
        array $ranks,
    ): self {
        return new self(
            $id,
            $name,
            $country,
            $competitionIds,
            $ranks,
        );
    }

    public function jsonSerialize(): array
    {
        $singles = array_filter($this->ranks, fn (Rank $rank) => RankType::SINGLE === $rank->getRankType());
        $averages = array_filter($this->ranks, fn (Rank $rank) => RankType::AVERAGE === $rank->getRankType());

        return [
            'id' => $this->id,
            'name' => $this->name,
            'country' => $this->country,
            'numberOfCompetitions' => count($this->competitionIds),
            'competitionIds' => $this->competitionIds,
            'rank' => [
                'singles' => array_map(fn (Rank $rank) => [
                    'eventId' => $rank->getEventId(),
                    'best' => $rank->getBest(),
                    'rank' => [
                        'world' => $rank->getWorldRank(),
                        'continent' => $rank->getContinentRank(),
                        'country' => $rank->getCountryRank(),
                    ],
                ], array_values($singles)),
                'averages' => array_map(fn (Rank $rank) => [
                    'eventId' => $rank->getEventId(),
                    'best' => $rank->getBest(),
                    'rank' => [
                        'world' => $rank->getWorldRank(),
                        'continent' => $rank->getContinentRank(),
                        'country' => $rank->getCountryRank(),
                    ],
                ], array_values($averages)),
            ],
            'results' => [
            ],
        ];
    }
}
