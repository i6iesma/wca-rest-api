<?php

namespace App\Domain\Rank\BuildRankApi;

use App\Domain\ApiFileWriter;
use App\Domain\Rank\RankRepository;
use App\Infrastructure\Attribute\AsCommandHandler;
use App\Infrastructure\CQRS\CommandHandler\CommandHandler;
use App\Infrastructure\CQRS\DomainCommand;

#[AsCommandHandler]
readonly class BuildRankApiCommandHandler implements CommandHandler
{
    public function __construct(
        private RankRepository $rankRepository,
        private ApiFileWriter $apiFileWriter
    ) {
    }

    public function handle(DomainCommand $command): void
    {
        assert($command instanceof BuildRankApi);
    }
}
