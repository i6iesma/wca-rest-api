<?php

namespace App\Domain\Person\BuildPersonApi;

use App\Domain\ProgressAwareDomainCommand;
use App\Infrastructure\CQRS\DomainCommand;

class BuildPersonApi extends DomainCommand
{
    use ProgressAwareDomainCommand;
}
