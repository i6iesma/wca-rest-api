<?php

namespace App\Infrastructure\ValueObject\Time;

readonly class DateRange implements \JsonSerializable
{
    public function __construct(
        private SerializableDateTime $from,
        private SerializableDateTime $till)
    {
        if ($from > $till) {
            throw new \InvalidArgumentException('invalid DateRange: '.$from.' till '.$till);
        }
    }

    public static function fromFromDateAndTillDate(
        SerializableDateTime $from,
        SerializableDateTime $till): DateRange
    {
        return new self($from, $till);
    }

    public function jsonSerialize(): array
    {
        return [
            'from' => $this->from,
            'till' => $this->till,
        ];
    }
}
