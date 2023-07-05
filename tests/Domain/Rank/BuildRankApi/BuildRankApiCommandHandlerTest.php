<?php

namespace App\Tests\Domain\Rank\BuildRankApi;

use App\Domain\ApiFileWriter;
use App\Domain\Rank\BuildRankApi\BuildRankApi;
use App\Domain\Rank\BuildRankApi\BuildRankApiCommandHandler;
use App\Tests\DatabaseTestCase;
use App\Tests\SpyApiFileWriter;
use League\Flysystem\Filesystem;
use Spatie\Snapshots\MatchesSnapshots;

class BuildRankApiCommandHandlerTest extends DatabaseTestCase
{
    use MatchesSnapshots;

    private BuildRankApiCommandHandler $buildRankApiCommandHandler;
    private ApiFileWriter $apiFileWriter;

    public function testHandle(): void
    {
        $this->buildRankApiCommandHandler->handle(new BuildRankApi());
        $this->assertMatchesJsonSnapshot($this->apiFileWriter->getWrites());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->apiFileWriter = new SpyApiFileWriter($this->createMock(Filesystem::class));
        $this->getContainer()->set(ApiFileWriter::class, $this->apiFileWriter);

        $this->buildRankApiCommandHandler = $this->getContainer()->get(BuildRankApiCommandHandler::class);
    }
}
