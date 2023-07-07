<?php

namespace App\Domain\Competition\Championship\BuildChampionshipApi;

use App\Domain\ProgressAwareDomainCommand;
use App\Infrastructure\CQRS\DomainCommand;

class BuildChampionshipApi extends DomainCommand
{
    use ProgressAwareDomainCommand;
}
