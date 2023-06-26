<?php

namespace App\Console;

use App\Domain\Competition\BuildCompetitionApi\BuildCompetitionApi;
use App\Domain\Country\BuildCountryApi\BuildCountryApi;
use App\Domain\Event\BuildEventApi\BuildEventApi;
use App\Domain\Person\BuildPersonApi\BuildPersonApi;
use App\Domain\Version\UpdateApiVersion\UpdateApiVersion;
use App\Infrastructure\CQRS\CommandBus;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:api:build', description: 'Build API')]
class GenerateStaticApiFilesConsoleCommand extends Command
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly Connection $connection,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Building country API...');
        $this->commandBus->dispatch(new BuildCountryApi());
        $output->writeln('Building event API...');
        $this->commandBus->dispatch(new BuildEventApi());
        $output->writeln('Building competition API...');
        $this->commandBus->dispatch(new BuildCompetitionApi());
        $output->writeln('Building person API...');
        $this->commandBus->dispatch(new BuildPersonApi());
        $output->writeln('Updating API version...');
        $this->commandBus->dispatch(new UpdateApiVersion());

        return Command::SUCCESS;
    }
}
