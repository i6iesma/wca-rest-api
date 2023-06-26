<?php

namespace App\Domain\Person\BuildPersonApi;

use App\Domain\ApiFileWriter;
use App\Domain\Person\PersonRepository;
use App\Infrastructure\Attribute\AsCommandHandler;
use App\Infrastructure\CQRS\CommandHandler\CommandHandler;
use App\Infrastructure\CQRS\DomainCommand;
use App\Infrastructure\Overview\Pagination;
use App\Infrastructure\Serialization\Json;

#[AsCommandHandler]
readonly class BuildPersonApiCommandHandler implements CommandHandler
{
    public function __construct(
        private PersonRepository $personRepository,
        private ApiFileWriter $apiFileWriter
    ) {
    }

    public function handle(DomainCommand $command): void
    {
        assert($command instanceof BuildPersonApi);

        $overview = $this->personRepository->findOneBy(
            Pagination::default(),
        );

        $this->apiFileWriter->write('persons', Json::encode($overview));
    }
}
