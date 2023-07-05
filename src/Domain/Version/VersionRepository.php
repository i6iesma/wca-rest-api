<?php

namespace App\Domain\Version;

use App\Domain\ApiFileWriter;
use App\Infrastructure\Environment\Settings;
use App\Infrastructure\Serialization\Json;
use App\Infrastructure\ValueObject\Time\SerializableDateTime;

readonly class VersionRepository
{
    public function __construct(
        private ApiFileWriter $apiFileWriter
    ) {
    }

    public function save(string $versionInfo): void
    {
        $this->apiFileWriter->write('version', $versionInfo);
    }

    public function getLastExportDate(): SerializableDateTime
    {
        $content = file_get_contents(Settings::getAppRoot().'/api/version.json');

        return SerializableDateTime::fromString(Json::decode($content)['export_date']);
    }
}
