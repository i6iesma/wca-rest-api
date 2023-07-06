<?php

namespace App\Domain\Event\BuildEventApi;

use App\Domain\ApiFileWriter;
use App\Domain\Event\EventRepository;
use App\Infrastructure\Attribute\AsCommandHandler;
use App\Infrastructure\CQRS\CommandHandler\CommandHandler;
use App\Infrastructure\CQRS\DomainCommand;
use App\Infrastructure\Serialization\Json;

#[AsCommandHandler]
readonly class BuildEventApiCommandHandler implements CommandHandler
{
    public function __construct(
        private EventRepository $eventRepository,
        private ApiFileWriter $apiFileWriter
    ) {
    }

    public function handle(DomainCommand $command): void
    {
        assert($command instanceof BuildEventApi);

        $overview = $this->eventRepository->findAll();
        $this->apiFileWriter->write('events', Json::encode($overview));
    }
}
