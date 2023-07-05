<?php

namespace App\Tests\Domain\Continent\Country\BuildCountryApi;

use App\Domain\ApiFileWriter;
use App\Domain\Continent\Country\BuildCountryApi\BuildCountryApi;
use App\Domain\Continent\Country\BuildCountryApi\BuildCountryApiCommandHandler;
use App\Tests\DatabaseTestCase;
use App\Tests\SpyApiFileWriter;
use League\Flysystem\Filesystem;
use Spatie\Snapshots\MatchesSnapshots;

class BuildCountryApiCommandHandlerTest extends DatabaseTestCase
{
    use MatchesSnapshots;

    private BuildCountryApiCommandHandler $buildCountryApiCommandHandler;
    private ApiFileWriter $apiFileWriter;

    public function testHandle(): void
    {
        $this->buildCountryApiCommandHandler->handle(new BuildCountryApi());
        $this->assertMatchesJsonSnapshot($this->apiFileWriter->getWrites());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->apiFileWriter = new SpyApiFileWriter($this->createMock(Filesystem::class));
        $this->getContainer()->set(ApiFileWriter::class, $this->apiFileWriter);

        $this->buildCountryApiCommandHandler = $this->getContainer()->get(BuildCountryApiCommandHandler::class);
    }
}
