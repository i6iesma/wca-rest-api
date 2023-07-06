<?php

namespace App\Tests\Domain\Competition\Championship\BuildChampionshipApi;

use App\Domain\ApiFileWriter;
use App\Domain\Competition\Championship\BuildChampionshipApi\BuildChampionshipApi;
use App\Domain\Competition\Championship\BuildChampionshipApi\BuildChampionshipApiCommandHandler;
use App\Tests\DatabaseTestCase;
use App\Tests\SpyApiFileWriter;
use League\Flysystem\Filesystem;
use Spatie\Snapshots\MatchesSnapshots;

class BuildChampionshipApiCommandHandlerTest extends DatabaseTestCase
{
    use MatchesSnapshots;

    private BuildChampionshipApiCommandHandler $buildChampionshipApiCommandHandler;
    private ApiFileWriter $apiFileWriter;

    public function testHandle(): void
    {
        $this->buildChampionshipApiCommandHandler->handle(new BuildChampionshipApi());
        $this->assertMatchesJsonSnapshot($this->apiFileWriter->getWrites());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->apiFileWriter = new SpyApiFileWriter($this->createMock(Filesystem::class));
        $this->getContainer()->set(ApiFileWriter::class, $this->apiFileWriter);

        $this->buildChampionshipApiCommandHandler = $this->getContainer()->get(BuildChampionshipApiCommandHandler::class);
    }
}
