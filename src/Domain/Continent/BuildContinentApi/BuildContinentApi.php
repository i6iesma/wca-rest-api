<?php

namespace App\Domain\Continent\BuildContinentApi;

use App\Domain\ProgressAwareDomainCommand;
use App\Infrastructure\CQRS\DomainCommand;

class BuildContinentApi extends DomainCommand
{
    use ProgressAwareDomainCommand;
}
