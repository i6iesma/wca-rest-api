<?php

namespace App\Tests\Domain\Person\BuildPersonApi;

use App\Domain\ApiFileWriter;
use App\Domain\Person\BuildPersonApi\BuildPersonApi;
use App\Domain\Person\BuildPersonApi\BuildPersonApiCommandHandler;
use App\Tests\DatabaseTestCase;
use App\Tests\SpyApiFileWriter;
use League\Flysystem\Filesystem;
use Spatie\Snapshots\MatchesSnapshots;

class BuildPersonApiCommandHandlerTest extends DatabaseTestCase
{
    use MatchesSnapshots;

    private BuildPersonApiCommandHandler $buildPersonApiCommandHandler;
    private ApiFileWriter $apiFileWriter;

    public function testHandle(): void
    {
        $this->buildPersonApiCommandHandler->handle(new BuildPersonApi());
        $this->assertMatchesJsonSnapshot($this->apiFileWriter->getWrites());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->apiFileWriter = new SpyApiFileWriter($this->createMock(Filesystem::class));
        $this->getContainer()->set(ApiFileWriter::class, $this->apiFileWriter);

        $this->buildPersonApiCommandHandler = $this->getContainer()->get(BuildPersonApiCommandHandler::class);
    }
}
