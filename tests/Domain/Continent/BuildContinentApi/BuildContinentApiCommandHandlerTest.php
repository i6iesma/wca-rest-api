<?php

namespace App\Tests\Domain\Continent\BuildContinentApi;

use App\Domain\ApiFileWriter;
use App\Domain\Continent\BuildContinentApi\BuildContinentApi;
use App\Domain\Continent\BuildContinentApi\BuildContinentApiCommandHandler;
use App\Tests\DatabaseTestCase;
use App\Tests\SpyApiFileWriter;
use League\Flysystem\Filesystem;
use Spatie\Snapshots\MatchesSnapshots;

class BuildContinentApiCommandHandlerTest extends DatabaseTestCase
{
    use MatchesSnapshots;

    private BuildContinentApiCommandHandler $buildContinentApiCommandHandler;
    private ApiFileWriter $apiFileWriter;

    public function testHandle(): void
    {
        $this->buildContinentApiCommandHandler->handle(new BuildContinentApi());
        $this->assertMatchesJsonSnapshot($this->apiFileWriter->getWrites());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->apiFileWriter = new SpyApiFileWriter($this->createMock(Filesystem::class));
        $this->getContainer()->set(ApiFileWriter::class, $this->apiFileWriter);

        $this->buildContinentApiCommandHandler = $this->getContainer()->get(BuildContinentApiCommandHandler::class);
    }
}
