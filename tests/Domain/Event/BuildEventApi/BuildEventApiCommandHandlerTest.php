<?php

namespace App\Tests\Domain\Event\BuildEventApi;

use App\Console\Progress;
use App\Domain\ApiFileWriter;
use App\Domain\Event\BuildEventApi\BuildEventApi;
use App\Domain\Event\BuildEventApi\BuildEventApiCommandHandler;
use App\Tests\DatabaseTestCase;
use App\Tests\SpyApiFileWriter;
use League\Flysystem\Filesystem;
use Spatie\Snapshots\MatchesSnapshots;
use Symfony\Component\Console\Output\OutputInterface;

class BuildEventApiCommandHandlerTest extends DatabaseTestCase
{
    use MatchesSnapshots;

    private BuildEventApiCommandHandler $buildEventApiCommandHandler;
    private ApiFileWriter $apiFileWriter;

    public function testHandle(): void
    {
        $this->buildEventApiCommandHandler->handle(new BuildEventApi(
            new Progress($this->createMock(OutputInterface::class))
        ));
        $this->assertMatchesJsonSnapshot($this->apiFileWriter->getWrites());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->apiFileWriter = new SpyApiFileWriter($this->createMock(Filesystem::class));
        $this->getContainer()->set(ApiFileWriter::class, $this->apiFileWriter);

        $this->buildEventApiCommandHandler = $this->getContainer()->get(BuildEventApiCommandHandler::class);
    }
}
