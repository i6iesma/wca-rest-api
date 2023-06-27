<?php

namespace App\Domain\Version\UpdateApiVersion;

use App\Domain\ApiFileWriter;
use App\Infrastructure\Attribute\AsCommandHandler;
use App\Infrastructure\CQRS\CommandHandler\CommandHandler;
use App\Infrastructure\CQRS\DomainCommand;
use App\Infrastructure\Environment\Settings;
use App\Infrastructure\Serialization\Json;
use App\Infrastructure\ValueObject\Time\SerializableDateTime;

#[AsCommandHandler]
readonly class UpdateApiVersionCommandHandler implements CommandHandler
{
    public function __construct(
        private ApiFileWriter $apiFileWriter
    ) {
    }

    public function handle(DomainCommand $command): void
    {
        assert($command instanceof UpdateApiVersion);
        $versionInfo = file_get_contents('https://www.worldcubeassociation.org/api/v0/export/public');

        $exportDate = SerializableDateTime::fromString(Json::decode($versionInfo)['export_date']);

        $readMe = file_get_contents(Settings::getAppRoot().'/README.MD');
        $readMe = preg_replace(
            '/<!--START_SECTION:version-date-->[\s\S]+<!--END_SECTION:version-date-->/',
            sprintf('<!--START_SECTION:version-date-->%s<!--END_SECTION:version-date-->', $exportDate->format('F d, Y')),
            $readMe
        );
        file_put_contents(Settings::getAppRoot().'/README.md', $readMe);

        $this->apiFileWriter->write('version', $versionInfo);
    }
}
