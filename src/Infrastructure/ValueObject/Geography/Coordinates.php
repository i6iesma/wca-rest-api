<?php

namespace App\Infrastructure\ValueObject\Geography;

readonly class Coordinates implements \JsonSerializable
{
    public function __construct(
        private Latitude $latitude,
        private Longitude $longitude)
    {
    }

    public static function fromIntegers(
        int $latitude,
        int $longitude
    ): self {
        // -2422498 => -2.422498
        // -54712597 => -54.712597

        // 51211996 => 51.211996
        // 4409566 => 4.409566

        return new self(
            Latitude::fromString($latitude / 1000000),
            Longitude::fromString($longitude / 1000000)
        );
    }

    public function getLatitude(): Latitude
    {
        return $this->latitude;
    }

    public function getLongitude(): Longitude
    {
        return $this->longitude;
    }

    public function jsonSerialize(): array
    {
        return [
            'latitude' => $this->getLatitude(),
            'longitude' => $this->getLongitude(),
        ];
    }
}
