<?php

namespace App\Domain\Competition\BuildCompetitionApi;

use App\Domain\ApiFileWriter;
use App\Domain\Competition\CompetitionRepository;
use App\Domain\Continent\Country\CountryRepository;
use App\Domain\Event\EventRepository;
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
        private EventRepository $eventRepository,
        private ApiFileWriter $apiFileWriter
    ) {
    }

    public function handle(DomainCommand $command): void
    {
        assert($command instanceof BuildCompetitionApi);

        $this->buildAllCompetitions();
        $this->buildCompetitionsPerCountry();
        $this->buildCompetitionsPerYear();
        $this->buildCompetitionsPerEvent();
    }

    private function buildAllCompetitions(): void
    {
        $overview = $this->competitionRepository->findOneBy(
            Pagination::default(),
        );

        $this->apiFileWriter->write('competitions', Json::encode($overview));

        $pagination = Pagination::default();
        do {
            $overview = $this->competitionRepository->findOneBy(
                $pagination,
            );

            $this->apiFileWriter->writeWithPagination(
                'competitions',
                $pagination,
                Json::encode($overview)
            );

            /** @var \App\Domain\Competition\Competition $item */
            foreach ($overview->getItems() as $item) {
                $this->apiFileWriter->write('competitions/'.$item->getId(), Json::encode($item));
            }

            $pagination = $pagination->next();
        } while (($pagination->getPageNumber() - 1) * $pagination->getPageSize() < $overview->getTotal());
    }

    private function buildCompetitionsPerCountry(): void
    {
        $countries = $this->countryRepository->findAll();

        /** @var \App\Domain\Continent\Country\Country $country */
        foreach ($countries->getItems() as $country) {
            $overview = $this->competitionRepository->findOneBy(
                Pagination::fromOffsetAndLimit(0, 10000),
                country: $country
            );
            $this->apiFileWriter->write(
                'competitions/'.$country->getIso2Code(),
                Json::encode($overview)
            );
        }
    }

    private function buildCompetitionsPerYear(): void
    {
        foreach (range(1980, (int) date('Y') + 1) as $year) {
            $overview = $this->competitionRepository->findOneBy(
                Pagination::fromOffsetAndLimit(0, 10000),
                year: $year
            );
            if ($overview->isEmpty()) {
                continue;
            }
            $this->apiFileWriter->write(
                'competitions/'.$year,
                Json::encode($overview)
            );
        }
    }

    private function buildCompetitionsPerEvent(): void
    {
        $events = $this->eventRepository->findAll();
        /** @var \App\Domain\Event\Event $event */
        foreach ($events->getItems() as $event) {
            $overview = $this->competitionRepository->findOneBy(
                Pagination::default(),
                eventId: $event->getId()
            );
            if ($overview->isEmpty()) {
                continue;
            }
            $this->apiFileWriter->write(
                'competitions/'.$event->getId(),
                Json::encode($overview)
            );

            $pagination = Pagination::default();
            do {
                $overview = $this->competitionRepository->findOneBy(
                    $pagination,
                    eventId: $event->getId()
                );

                $this->apiFileWriter->writeWithPagination(
                    'competitions/'.$event->getId(),
                    $pagination,
                    Json::encode($overview)
                );

                $pagination = $pagination->next();
            } while (($pagination->getPageNumber() - 1) * $pagination->getPageSize() < $overview->getTotal());
        }
    }
}
