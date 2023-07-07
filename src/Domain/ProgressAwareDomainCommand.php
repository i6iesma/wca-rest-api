<?php

namespace App\Domain;

use Symfony\Component\Console\Helper\ProgressBar;

trait ProgressAwareDomainCommand
{
    public function __construct(
        private readonly ProgressBar $progressBar
    ) {
    }

    public function getProgressBar(): ProgressBar
    {
        return $this->progressBar;
    }
}
