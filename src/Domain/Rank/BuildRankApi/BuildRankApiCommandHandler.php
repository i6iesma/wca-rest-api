<?php

namespace App\Domain\Rank\BuildRankApi;

use App\Domain\ApiFileWriter;
use App\Domain\Continent\Country\CountryRepository;
use App\Domain\Event\EventRepository;
use App\Domain\Rank\RankRepository;
use App\Domain\Rank\RankType;
use App\Domain\Rank\RegionType;
use App\Infrastructure\Attribute\AsCommandHandler;
use App\Infrastructure\CQRS\CommandHandler\CommandHandler;
use App\Infrastructure\CQRS\DomainCommand;
use App\Infrastructure\Overview\Pagination;
use App\Infrastructure\Serialization\Json;

#[AsCommandHandler]
readonly class BuildRankApiCommandHandler implements CommandHandler
{
    public function __construct(
        private RankRepository $rankRepository,
        private EventRepository $eventRepository,
        private CountryRepository $countryRepository,
        private ApiFileWriter $apiFileWriter
    ) {
    }

    public function handle(DomainCommand $command): void
    {
        assert($command instanceof BuildRankApi);

        $events = $this->eventRepository->findAll();
        $countries = $this->countryRepository->findAll();

        foreach (RankType::cases() as $rankType) {
            /** @var \App\Domain\Event\Event $event */
            foreach ($events->getItems() as $event) {
                $overview = $this->rankRepository->findOneBy(
                    Pagination::default(),
                    $rankType,
                    RegionType::WORLD,
                    $event->getId()
                );

                if ($overview->isEmpty()) {
                    continue;
                }
                $this->apiFileWriter->write(
                    sprintf(
                        'rank/%s/%s/%s',
                        'world',
                        $rankType->value,
                        $event->getId()
                    ),
                    Json::encode($overview)
                );
            }
        }

        /** @var \App\Domain\Continent\Country\Country $country */
        foreach ($countries->getItems() as $country) {
            foreach (RankType::cases() as $rankType) {
                /** @var \App\Domain\Event\Event $event */
                foreach ($events->getItems() as $event) {
                    $overview = $this->rankRepository->findOneBy(
                        Pagination::default(),
                        $rankType,
                        RegionType::COUNTRY,
                        $event->getId(),
                        $country->getIso2Code()
                    );

                    if ($overview->isEmpty()) {
                        continue;
                    }
                    $this->apiFileWriter->write(
                        sprintf(
                            'rank/%s/%s/%s',
                            $country->getIso2Code(),
                            $rankType->value,
                            $event->getId()
                        ),
                        Json::encode($overview)
                    );
                }
            }
        }
    }
}
