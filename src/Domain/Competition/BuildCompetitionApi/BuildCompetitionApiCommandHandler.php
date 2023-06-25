<?php

namespace App\Domain\Competition\BuildCompetitionApi;

use App\Domain\ApiFileWriter;
use App\Domain\Competition\CompetitionRepository;
use App\Domain\Country\CountryRepository;
use App\Infrastructure\Attribute\AsCommandHandler;
use App\Infrastructure\CQRS\CommandHandler\CommandHandler;
use App\Infrastructure\CQRS\DomainCommand;
use App\Infrastructure\Overview\Pagination;
use App\Infrastructure\Serialization\Json;

#[AsCommandHandler]
readonly class BuildCompetitionApiCommandHandler implements CommandHandler
{
    public function __construct(
        private CompetitionRepository $competitionRepository,
        private CountryRepository $countryRepository,
        private ApiFileWriter $apiFileWriter
    ) {
    }

    public function handle(DomainCommand $command): void
    {
        assert($command instanceof BuildCompetitionApi);

        $overview = $this->competitionRepository->findOneBy(
            Pagination::default(),
        );

        $this->apiFileWriter->write('competition', Json::encode($overview));

        $pagination = Pagination::default();
        do {
            $overview = $this->competitionRepository->findOneBy(
                $pagination,
            );

            $this->apiFileWriter->writeWithPagination(
                'competition',
                $pagination,
                Json::encode($overview)
            );

            /** @var \App\Domain\Competition\Competition $item */
            foreach ($overview->getItems() as $item) {
                $this->apiFileWriter->write('competition/'.$item->getId(), Json::encode($item));
            }

            $pagination = $pagination->next();
        } while (($pagination->getPageNumber() - 1) * $pagination->getPageSize() < $overview->getTotal());

        $countries = $this->countryRepository->findAll();

        /** @var \App\Domain\Country\Country $country */
        foreach ($countries->getItems() as $country) {
            $overview = $this->competitionRepository->findOneBy(
                Pagination::fromOffsetAndLimit(0, 10000),
                $country
            );
            $this->apiFileWriter->write(
                'competition/country/'.$country->getIso2Code(),
                Json::encode($overview)
            );
        }

        foreach (range(1980, (int) date('Y') + 1) as $year) {
            $overview = $this->competitionRepository->findOneBy(
                Pagination::fromOffsetAndLimit(0, 10000),
                null,
                $year
            );
            if ($overview->isEmpty()) {
                continue;
            }
            $this->apiFileWriter->write(
                'competition/year/'.$year,
                Json::encode($overview)
            );
        }
    }
}
