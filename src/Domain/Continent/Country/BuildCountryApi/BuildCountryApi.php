<?php

namespace App\Domain\Continent\Country\BuildCountryApi;

use App\Domain\ProgressAwareDomainCommand;
use App\Infrastructure\CQRS\DomainCommand;

class BuildCountryApi extends DomainCommand
{
    use ProgressAwareDomainCommand;
}
