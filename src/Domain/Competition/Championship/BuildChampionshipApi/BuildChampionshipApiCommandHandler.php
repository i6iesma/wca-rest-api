<?php

namespace App\Domain\Competition\Championship\BuildChampionshipApi;

use App\Domain\Competition\Championship\ChampionshipRepository;
use App\Domain\FileWriter;
use App\Infrastructure\Attribute\AsCommandHandler;
use App\Infrastructure\CQRS\CommandHandler\CommandHandler;
use App\Infrastructure\CQRS\DomainCommand;
use App\Infrastructure\Serialization\Json;

#[AsCommandHandler]
readonly class BuildChampionshipApiCommandHandler implements CommandHandler
{
    public function __construct(
        private ChampionshipRepository $championshipRepository,
        private FileWriter $apiFileWriter
    ) {
    }

    public function handle(DomainCommand $command): void
    {
        assert($command instanceof BuildChampionshipApi);

        $progressBar = $command->getProgressBar();
        $progressBar->start();

        $overview = $this->championshipRepository->findAll();
        $progressBar->setMaxSteps($overview->getTotal() + 1);

        $this->apiFileWriter->write('championships', Json::encode($overview));
        $progressBar->advance();

        /** @var \App\Domain\Competition\Championship\Championship $item */
        foreach ($overview->getItems() as $item) {
            $this->apiFileWriter->write('championships/'.$item->getId(), Json::encode($item));
            $progressBar->advance();
        }

        $progressBar->finish();
    }
}
