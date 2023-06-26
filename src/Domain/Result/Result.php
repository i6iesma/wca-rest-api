<?php

namespace App\Domain\Result;

use App\Infrastructure\Overview\Item;

readonly class Result implements Item
{
    private function __construct(
        private string $competitionId,
        private string $personId,
        private string $eventId,
        private string $round,
        private int $position,
        private int $best,
        private int $average,
        private string $format,
        private array $solves,
    ) {
    }

    public static function fromState(
        string $competitionId,
        string $personId,
        string $eventId,
        string $round,
        int $position,
        int $best,
        int $average,
        string $format,
        array $solves,
    ): self {
        return new self(
            $competitionId,
            $personId,
            $eventId,
            $round,
            $position,
            $best,
            $average,
            $format,
            $solves,
        );
    }

    public function getCompetitionId(): string
    {
        return $this->competitionId;
    }

    public function getEventId(): string
    {
        return $this->eventId;
    }

    public function getRound(): string
    {
        return $this->round;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getBest(): int
    {
        return $this->best;
    }

    public function getAverage(): int
    {
        return $this->average;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function getSolves(): array
    {
        return $this->solves;
    }

    public function jsonSerialize(): array
    {
        return [
            'competitionId' => $this->competitionId,
            'personId' => $this->personId,
            'eventId' => $this->eventId,
            'round' => $this->round,
            'position' => $this->position,
            'best' => $this->best,
            'average' => $this->average,
            'format' => $this->format,
            'solves' => $this->solves,
        ];
    }
}
