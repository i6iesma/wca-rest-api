<?php

namespace App\Console;

use App\Domain\Competition\BuildCompetitionApi\BuildCompetitionApi;
use App\Domain\Country\BuildCountryApi\BuildCountryApi;
use App\Domain\Event\BuildEventApi\BuildEventApi;
use App\Domain\Person\BuildPersonApi\BuildPersonApi;
use App\Domain\Version\UpdateApiVersion\UpdateApiVersion;
use App\Infrastructure\CQRS\CommandBus;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:api:build', description: 'Build API')]
class GenerateStaticApiFilesConsoleCommand extends Command
{
    public function __construct(
        private readonly CommandBus $commandBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('apisToRebuild', InputArgument::REQUIRED, 'Comma separated list of the APIs to rebuild');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $apisToRebuild = explode(',', $input->getArgument('apisToRebuild'));

        if (in_array('country', $apisToRebuild)) {
            $output->writeln('Building country API...');
            $this->commandBus->dispatch(new BuildCountryApi());
        }
        if (in_array('event', $apisToRebuild)) {
            $output->writeln('Building event API...');
            $this->commandBus->dispatch(new BuildEventApi());
        }
        if (in_array('competition', $apisToRebuild)) {
            $output->writeln('Building competition API...');
            $this->commandBus->dispatch(new BuildCompetitionApi());
        }
        if (in_array('person', $apisToRebuild)) {
            $output->writeln('Building person API...');
            $this->commandBus->dispatch(new BuildPersonApi());
        }

        $output->writeln('Updating API version...');
        $this->commandBus->dispatch(new UpdateApiVersion());

        return Command::SUCCESS;
    }
}
