<?php

namespace App\Console;

use App\Domain\Competition\BuildCompetitionApi\BuildCompetitionApi;
use App\Infrastructure\CQRS\CommandBus;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:api:build', description: 'Build API')]
class GenerateStaticApiFilesConsoleCommand extends Command
{
    public function __construct(
        private readonly CommandBus $commandBus
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->commandBus->dispatch(new BuildCompetitionApi());

        return Command::SUCCESS;
    }
}
