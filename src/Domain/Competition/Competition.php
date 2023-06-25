<?php

namespace App\Domain\Competition;

use App\Infrastructure\Overview\Item;
use App\Infrastructure\ValueObject\Country;
use App\Infrastructure\ValueObject\Time\DateRange;

readonly class Competition implements Item
{
    private function __construct(
        private string $id,
        private string $name,
        private string $city,
        private Country $country,
        private DateRange $date,
        private bool $isCanceled,
        private array $events,
        private string $wcaDelegate,
        private string $organiser,
        private Venue $venue,
        private ?string $information = null,
        private ?string $externalWebsite = null,
    ) {
    }

    public static function fromState(
        string $id,
        string $name,
        string $city,
        Country $country,
        DateRange $date,
        bool $isCanceled,
        array $events,
        string $wcaDelegate,
        string $organiser,
        Venue $venue,
        string $information = null,
        string $externalWebsite = null,
    ): self {
        return new self(
            $id,
            $name,
            $city,
            $country,
            $date,
            $isCanceled,
            $events,
            $wcaDelegate,
            $organiser,
            $venue,
            $information,
            $externalWebsite,
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'city' => $this->city,
            'country' => $this->country,
            'date' => $this->date,
            'isCanceled' => $this->isCanceled,
            'events' => $this->events,
            'wcaDelegate' => $this->wcaDelegate,
            'organiser' => $this->organiser,
            'venue' => $this->venue,
            'information' => $this->information,
            'externalWebsite' => $this->externalWebsite,
        ];
    }
}
