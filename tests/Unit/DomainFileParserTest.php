<?php

namespace Tests\Unit;

use App\Utils\DomainsFileParser;
use Illuminate\Contracts\Filesystem\Filesystem;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class DomainFileParserTest extends TestCase
{
    /** @var MockObject|Filesystem $storageMock */
    protected $storageMock;

    public function setUp()
    {
        parent::setUp();
        $this->storageMock = $this->getMockBuilder(Filesystem::class)->getMock();
    }

    public function testTodayPoolFileExists()
    {
        $dirPath = 'pool_downloads';
        $datetime = new \DateTime('2018-10-28');

        $this->storageMock->method('exists')->willReturn(true);

        $expectedPoolFilePath = 'pool_downloads/10_28_2018-pool/list.txt';

        $parser = new DomainsFileParser($this->storageMock);
        $this->assertEquals($expectedPoolFilePath, $parser->findPoolFile($dirPath, $datetime));
    }

    public function testFileNotFound()
    {
        $dirPath = 'pool_downloads';
        $datetime = new \DateTime('2018-11-28');

        $this->storageMock->method('exists')->willReturn(false);

        $parser = new DomainsFileParser($this->storageMock);
        $this->assertEquals(false, $parser->findPoolFile($dirPath, $datetime));
    }
}