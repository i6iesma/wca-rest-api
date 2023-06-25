<?php

namespace App\Infrastructure\ValueObject\Time;

class SerializableDateTime extends \DateTimeImmutable implements \JsonSerializable
{
    public static function fromString(string $string): self
    {
        return new static($string);
    }

    public static function fromOptionalString(string $string = null): ?SerializableDateTime
    {
        return $string ? self::fromString($string) : null;
    }

    public function jsonSerialize(): string
    {
        return $this->iso();
    }

    public function iso(): string
    {
        return $this->format('Y-m-d H:i:s');
    }
}
