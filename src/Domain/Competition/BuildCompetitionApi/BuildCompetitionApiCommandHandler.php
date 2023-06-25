<?php

namespace App\Domain\Competition\BuildCompetitionApi;

use App\Domain\ApiFileWriter;
use App\Domain\Competition\CompetitionRepository;
use App\Infrastructure\Attribute\AsCommandHandler;
use App\Infrastructure\CQRS\CommandHandler\CommandHandler;
use App\Infrastructure\CQRS\DomainCommand;
use App\Infrastructure\Overview\Pagination;
use App\Infrastructure\Overview\Sorting\Sorting;
use App\Infrastructure\Overview\Sorting\SortingDirection;
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

        $overview = $this->competitionRepository->findAll(
            Pagination::default(),
            Sorting::with('id', SortingDirection::ASCENDING)
        );

        $this->apiFileWriter->write('competition', Json::encode($overview));

        $pagination = Pagination::default();
        do {
            $overview = $this->competitionRepository->findAll(
                $pagination,
                Sorting::with('id', SortingDirection::ASCENDING)
            );

            $this->apiFileWriter->writeWithPagination(
                'competition',
                $pagination,
                Json::encode($overview)
            );
            $pagination = $pagination->next();
        } while (($pagination->getPageNumber() - 1) * $pagination->getPageSize() < $overview->getTotal());
    }
}
