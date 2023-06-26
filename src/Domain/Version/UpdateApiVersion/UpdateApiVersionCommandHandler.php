<?php

namespace App\Domain\Version\UpdateApiVersion;

use App\Domain\ApiFileWriter;
use App\Infrastructure\Attribute\AsCommandHandler;
use App\Infrastructure\CQRS\CommandHandler\CommandHandler;
use App\Infrastructure\CQRS\DomainCommand;

#[AsCommandHandler]
class UpdateApiVersionCommandHandler implements CommandHandler
{
    public function __construct(
        private ApiFileWriter $apiFileWriter
    ) {
    }

    public function handle(DomainCommand $command): void
    {
        assert($command instanceof UpdateApiVersion);
        $this->apiFileWriter->write('version', file_get_contents('https://www.worldcubeassociation.org/api/v0/export/public'));
    }
}
