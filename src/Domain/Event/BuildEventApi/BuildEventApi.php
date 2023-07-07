<?php

namespace App\Domain\Event\BuildEventApi;

use App\Domain\ProgressAwareDomainCommand;
use App\Infrastructure\CQRS\DomainCommand;

class BuildEventApi extends DomainCommand
{
    use ProgressAwareDomainCommand;
}
