<?php

namespace App\Tests\Domain\Competition\BuildCompetitionApi;

use App\Domain\ApiFileWriter;
use App\Domain\Competition\BuildCompetitionApi\BuildCompetitionApi;
use App\Domain\Competition\BuildCompetitionApi\BuildCompetitionApiCommandHandler;
use App\Tests\DatabaseTestCase;
use App\Tests\SpyApiFileWriter;
use League\Flysystem\Filesystem;
use Spatie\Snapshots\MatchesSnapshots;

class BuildCompetitionApiCommandHandlerTest extends DatabaseTestCase
{
    use MatchesSnapshots;

    private BuildCompetitionApiCommandHandler $buildCompetitionApiCommandHandler;
    private ApiFileWriter $apiFileWriter;

    public function testHandle(): void
    {
        $this->buildCompetitionApiCommandHandler->handle(new BuildCompetitionApi());
        $this->assertMatchesJsonSnapshot($this->apiFileWriter->getWrites());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->apiFileWriter = new SpyApiFileWriter($this->createMock(Filesystem::class));
        $this->getContainer()->set(ApiFileWriter::class, $this->apiFileWriter);

        $this->buildCompetitionApiCommandHandler = $this->getContainer()->get(BuildCompetitionApiCommandHandler::class);
    }
}
