<?php

namespace App\Domain\Competition\BuildCompetitionApi;

use App\Domain\ProgressAwareDomainCommand;
use App\Infrastructure\CQRS\DomainCommand;

class BuildCompetitionApi extends DomainCommand
{
    use ProgressAwareDomainCommand;
}
