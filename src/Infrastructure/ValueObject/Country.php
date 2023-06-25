<?php

namespace App\Infrastructure\ValueObject;

use App\Infrastructure\ValueObject\String\NonEmptyStringLiteral;

readonly class Country extends NonEmptyStringLiteral
{
    public function __construct(
        string $string,
    ) {
        parent::__construct($string);
    }

    public static function fromIso2Code(string $iso2): self
    {
        if (2 != strlen($iso2)) {
            throw new \InvalidArgumentException('Invalid ISO2 code');
        }

        return new self($iso2);
    }
}
