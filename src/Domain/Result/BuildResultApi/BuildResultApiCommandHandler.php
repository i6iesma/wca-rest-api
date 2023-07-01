<?php

namespace App\Domain\Result\BuildResultApi;

use App\Domain\ApiFileWriter;
use App\Domain\Competition\CompetitionRepository;
use App\Domain\Result\ResultRepository;
use App\Infrastructure\Attribute\AsCommandHandler;
use App\Infrastructure\CQRS\CommandHandler\CommandHandler;
use App\Infrastructure\CQRS\DomainCommand;
use App\Infrastructure\Overview\Pagination;
use App\Infrastructure\Serialization\Json;

#[AsCommandHandler]
readonly class BuildResultApiCommandHandler implements CommandHandler
{
    public function __construct(
        private ResultRepository $resultRepository,
        private CompetitionRepository $competitionRepository,
        private ApiFileWriter $apiFileWriter
    ) {
    }

    public function handle(DomainCommand $command): void
    {
        assert($command instanceof BuildResultApi);

        $competitions = $this->competitionRepository->findOneBy(
            Pagination::fromOffsetAndLimit(0, 100000)
        );

        /** @var \App\Domain\Competition\Competition $competition */
        foreach ($competitions->getItems() as $competition) {
            $overview = $this->resultRepository->findOneBy(
                Pagination::default(),
                $competition->getId()
            );

            if ($overview->isEmpty()) {
                continue;
            }

            $this->apiFileWriter->write(
                sprintf('results/%s', $competition->getId()),
                Json::encode($overview)
            );

            foreach ($competition->getEvents() as $eventId) {
                $overview = $this->resultRepository->findOneBy(
                    Pagination::default(),
                    $competition->getId(),
                    $eventId
                );

                if ($overview->isEmpty()) {
                    continue;
                }

                $this->apiFileWriter->write(
                    sprintf('results/%s/%s', $competition->getId(), $eventId),
                    Json::encode($overview)
                );
            }
        }
    }
}
