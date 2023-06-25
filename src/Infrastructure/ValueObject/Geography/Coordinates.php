<?php

namespace App\Infrastructure\ValueObject\Geography;

readonly class Coordinates implements \JsonSerializable
{
    public function __construct(
        private Latitude $latitude,
        private Longitude $longitude)
    {
    }

    public static function fromLatitudeAndLongitude(
        Latitude $latitude,
        Longitude $longitude
    ): self {
        return new self($latitude, $longitude);
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
