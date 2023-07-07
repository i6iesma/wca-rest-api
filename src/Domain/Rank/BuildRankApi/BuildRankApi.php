<?php

namespace App\Domain\Rank\BuildRankApi;

use App\Domain\ProgressAwareDomainCommand;
use App\Infrastructure\CQRS\DomainCommand;

class BuildRankApi extends DomainCommand
{
    use ProgressAwareDomainCommand;
}
