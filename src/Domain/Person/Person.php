<?php

namespace App\Domain\Person;

use App\Domain\Country\Iso2Code;
use App\Domain\Rank\Rank;
use App\Domain\Rank\RankType;
use App\Domain\Result\Result;
use App\Infrastructure\Overview\Item;
use App\Infrastructure\ValueObject\String\Slug;

readonly class Person implements Item
{
    private function __construct(
        private string $id,
        private string $name,
        private Iso2Code $country,
        private array $competitionIds,
        private array $ranks,
        private array $results,
    ) {
    }

    public static function fromState(
        string $id,
        string $name,
        Iso2Code $country,
        array $competitionIds,
        array $ranks,
        array $results,
    ): self {
        return new self(
            $id,
            $name,
            $country,
            $competitionIds,
            $ranks,
            $results,
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSlug(): Slug
    {
        return Slug::fromString($this->name);
    }

    public function jsonSerialize(): array
    {
        $singles = array_filter($this->ranks, fn (Rank $rank) => RankType::SINGLE === $rank->getRankType());
        $averages = array_filter($this->ranks, fn (Rank $rank) => RankType::AVERAGE === $rank->getRankType());

        $results = [];
        /** @var Result $result */
        foreach ($this->results as $result) {
            $results[$result->getCompetitionId()][$result->getEventId()][] = [
                'round' => $result->getRound(),
                'position' => $result->getPosition(),
                'best' => $result->getBest(),
                'average' => $result->getAverage(),
                'format' => $result->getFormat(),
                'solves' => $result->getSolves(),
            ];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->getSlug(),
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
            'results' => $results,
        ];
    }
}
