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
use Symfony\Component\Console\Helper\ProgressBar;
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
        // @TODO: add total execution time.
        $output->writeln('Building API...');
        $apisToRebuild = explode(',', $input->getArgument('apisToRebuild'));

        if (in_array('continent', $apisToRebuild)) {
            $output->writeln('  - Building continent API...');
            $this->commandBus->dispatch(new BuildContinentApi($this->initializeProgressBar($output)));
            $output->writeln('');
        }
        if (in_array('country', $apisToRebuild)) {
            $output->writeln('  - Building country API...');
            $this->commandBus->dispatch(new BuildCountryApi($this->initializeProgressBar($output)));
            $output->writeln('');
        }
        if (in_array('event', $apisToRebuild)) {
            $output->writeln('  - Building event API...');
            $this->commandBus->dispatch(new BuildEventApi($this->initializeProgressBar($output)));
            $output->writeln('');
        }
        if (in_array('competition', $apisToRebuild)) {
            $output->writeln('  - Building competition API...');
            $this->commandBus->dispatch(new BuildCompetitionApi($this->initializeProgressBar($output)));
            $output->writeln('');
        }
        if (in_array('championship', $apisToRebuild)) {
            $output->writeln('  - Building championship API...');
            $this->commandBus->dispatch(new BuildChampionshipApi($this->initializeProgressBar($output)));
            $output->writeln('');
        }
        if (in_array('person', $apisToRebuild)) {
            $output->writeln('  - Building person API...');
            $this->commandBus->dispatch(new BuildPersonApi($this->initializeProgressBar($output)));
            $output->writeln('');
        }
        if (in_array('rank', $apisToRebuild)) {
            $output->writeln('  - Building rank API...');
            $this->commandBus->dispatch(new BuildRankApi($this->initializeProgressBar($output)));
            $output->writeln('');
        }
        if (in_array('result', $apisToRebuild)) {
            $output->writeln('  - Building result API...');
            $this->commandBus->dispatch(new BuildResultApi($this->initializeProgressBar($output)));
            $output->writeln('');
        }
        if (in_array('version', $apisToRebuild)) {
            $output->writeln('  - Updating API version...');
            $this->commandBus->dispatch(new UpdateApiVersion());
        }

        return Command::SUCCESS;
    }

    private function initializeProgressBar(OutputInterface $output): ProgressBar
    {
        $progressBar = new ProgressBar($output, 0);
        $progressBar->setFormat('  %current%/%max% [%bar%] %percent:3s%% [%elapsed:6s%]');

        return $progressBar;
    }
}
