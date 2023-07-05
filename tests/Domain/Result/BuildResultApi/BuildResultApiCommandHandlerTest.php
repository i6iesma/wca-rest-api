<?php

namespace App\Tests\Domain\Result\BuildResultApi;

use App\Domain\ApiFileWriter;
use App\Domain\Result\BuildResultApi\BuildResultApi;
use App\Domain\Result\BuildResultApi\BuildResultApiCommandHandler;
use App\Tests\DatabaseTestCase;
use App\Tests\SpyApiFileWriter;
use League\Flysystem\Filesystem;
use Spatie\Snapshots\MatchesSnapshots;

class BuildResultApiCommandHandlerTest extends DatabaseTestCase
{
    use MatchesSnapshots;

    private BuildResultApiCommandHandler $buildResultApiCommandHandler;
    private ApiFileWriter $apiFileWriter;

    public function testHandle(): void
    {
        $this->buildResultApiCommandHandler->handle(new BuildResultApi());
        $this->assertMatchesJsonSnapshot($this->apiFileWriter->getWrites());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->apiFileWriter = new SpyApiFileWriter($this->createMock(Filesystem::class));
        $this->getContainer()->set(ApiFileWriter::class, $this->apiFileWriter);

        $this->buildResultApiCommandHandler = $this->getContainer()->get(BuildResultApiCommandHandler::class);
    }
}
