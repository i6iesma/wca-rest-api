<?php

namespace App\Domain\Competition\Championship\BuildChampionshipApi;

use App\Domain\ApiFileWriter;
use App\Domain\Competition\Championship\ChampionshipRepository;
use App\Infrastructure\Attribute\AsCommandHandler;
use App\Infrastructure\CQRS\CommandHandler\CommandHandler;
use App\Infrastructure\CQRS\DomainCommand;
use App\Infrastructure\Serialization\Json;

#[AsCommandHandler]
readonly class BuildChampionshipApiCommandHandler implements CommandHandler
{
    public function __construct(
        private ChampionshipRepository $championshipRepository,
        private ApiFileWriter $apiFileWriter
    ) {
    }

    public function handle(DomainCommand $command): void
    {
        assert($command instanceof BuildChampionshipApi);

        $overview = $this->championshipRepository->findAll();
        $this->apiFileWriter->write('championships', Json::encode($overview));
    }
}
