<?php

namespace App\Domain\Result\BuildResultApi;

use App\Domain\ProgressAwareDomainCommand;
use App\Infrastructure\CQRS\DomainCommand;

class BuildResultApi extends DomainCommand
{
    use ProgressAwareDomainCommand;
}
