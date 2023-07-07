<?php

namespace App\Console;

use App\Domain\Competition\BuildCompetitionApi\BuildCompetitionApi;
use App\Domain\Competition\Championship\BuildChampionshipApi\BuildChampionshipApi;
use App\Domain\Continent\BuildContinentApi\BuildContinentApi;
use App\Domain\Continent\Country\BuildCountryApi\BuildCountryApi;
use App\Domain\Event\BuildEventApi\BuildEventApi;
use App\Domain\Person\BuildPersonApi\BuildPersonApi;
use App\Domain\Rank\BuildRankApi\BuildRankApi;
use App\Domain\Result\BuildResultApi\BuildResultApi;
use App\Domain\Version\UpdateApiVersion\UpdateApiVersion;
use App\Infrastructure\CQRS\CommandBus;
use Lcobucci\Clock\Clock;
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
        private readonly Clock $clock,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('apisToRebuild', InputArgument::REQUIRED, 'Comma separated list of the APIs to rebuild');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Building API...');
        $apisToRebuild = explode(',', $input->getArgument('apisToRebuild'));

        if (in_array('continent', $apisToRebuild)) {
            $then = $this->clock->now();
            $output->write('  - Building continent API...');
            $this->commandBus->dispatch(new BuildContinentApi());
            $output->writeln(sprintf(' [%s sec]', $this->calculateExecutionTimeInSeconds($then)));
        }
        if (in_array('country', $apisToRebuild)) {
            $then = $this->clock->now();
            $output->write('  - Building country API...');
            $this->commandBus->dispatch(new BuildCountryApi());
            $output->writeln(sprintf(' [%s sec]', $this->calculateExecutionTimeInSeconds($then)));
        }
        if (in_array('event', $apisToRebuild)) {
            $then = $this->clock->now();
            $output->write('  - Building event API...');
            $this->commandBus->dispatch(new BuildEventApi());
            $output->writeln(sprintf(' [%s sec]', $this->calculateExecutionTimeInSeconds($then)));
        }
        if (in_array('competition', $apisToRebuild)) {
            $then = $this->clock->now();
            $output->write('  - Building competition API...');
            $this->commandBus->dispatch(new BuildCompetitionApi());
            $output->writeln(sprintf(' [%s sec]', $this->calculateExecutionTimeInSeconds($then)));
        }
        if (in_array('championship', $apisToRebuild)) {
            $then = $this->clock->now();
            $output->write('  - Building championship API...');
            $this->commandBus->dispatch(new BuildChampionshipApi());
            $output->writeln(sprintf(' [%s sec]', $this->calculateExecutionTimeInSeconds($then)));
        }
        if (in_array('person', $apisToRebuild)) {
            $then = $this->clock->now();
            $output->write('  - Building person API...');
            $this->commandBus->dispatch(new BuildPersonApi());
            $output->writeln(sprintf(' [%s sec]', $this->calculateExecutionTimeInSeconds($then)));
        }
        if (in_array('rank', $apisToRebuild)) {
            $then = $this->clock->now();
            $output->write('  - Building rank API...');
            $this->commandBus->dispatch(new BuildRankApi());
            $output->writeln(sprintf(' [%s sec]', $this->calculateExecutionTimeInSeconds($then)));
        }
        if (in_array('result', $apisToRebuild)) {
            $then = $this->clock->now();
            $output->write('  - Building result API...');
            $this->commandBus->dispatch(new BuildResultApi());
            $output->writeln(sprintf(' [%s sec]', $this->calculateExecutionTimeInSeconds($then)));
        }
        if (in_array('version', $apisToRebuild)) {
            $then = $this->clock->now();
            $output->write('  - Updating API version...');
            $this->commandBus->dispatch(new UpdateApiVersion());
            $output->writeln(sprintf(' [%s sec]', $this->calculateExecutionTimeInSeconds($then)));
        }

        return Command::SUCCESS;
    }

    private function calculateExecutionTimeInSeconds(\DateTimeImmutable $then): int
    {
        return $this->clock->now()->getTimestamp() - $then->getTimestamp();
    }
}
