<?php

namespace App\Domain\Version;

use App\Domain\ApiFileWriter;

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
}
