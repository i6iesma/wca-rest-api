<?php

namespace App\Domain\Country\BuildCountryApi;

use App\Domain\ApiFileWriter;
use App\Domain\Country\CountryRepository;
use App\Infrastructure\Attribute\AsCommandHandler;
use App\Infrastructure\CQRS\CommandHandler\CommandHandler;
use App\Infrastructure\CQRS\DomainCommand;
use App\Infrastructure\Serialization\Json;

#[AsCommandHandler]
readonly class BuildCountryApiCommandHandler implements CommandHandler
{
    public function __construct(
        private CountryRepository $countryRepository,
        private ApiFileWriter $apiFileWriter
    ) {
    }

    public function handle(DomainCommand $command): void
    {
        assert($command instanceof BuildCountryApi);

        $overview = $this->countryRepository->findAll();

        $this->apiFileWriter->write('country', Json::encode($overview));
    }
}
