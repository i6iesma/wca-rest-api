<?php

namespace App\Domain\Competition\BuildCompetitionApi;

use App\Domain\ApiFileWriter;
use App\Domain\Competition\CompetitionRepository;
use App\Infrastructure\Attribute\AsCommandHandler;
use App\Infrastructure\CQRS\CommandHandler\CommandHandler;
use App\Infrastructure\CQRS\DomainCommand;
use App\Infrastructure\Serialization\Json;

#[AsCommandHandler]
readonly class BuildCompetitionApiCommandHandler implements CommandHandler
{
    public function __construct(
        private CompetitionRepository $competitionRepository,
        private ApiFileWriter $apiFileWriter
    ) {
    }

    public function handle(DomainCommand $command): void
    {
        assert($command instanceof BuildCompetitionApi);

        // $overview = $this->competitionRepository->findAll();

        $this->apiFileWriter->write('competition', Json::encode(['test']));
    }
}
