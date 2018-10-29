<?php

namespace Tests\Unit;

use App\Utils\DomainsFileParser;
use Illuminate\Contracts\Filesystem\Filesystem;
use Tests\TestCase;

class DomainFileParserTest extends TestCase
{
    public function testTodayPoolFileExists()
    {
        $dirPath = 'pool_downloads';
        $datetime = new \DateTime('2018-10-28');

        $storageMock = $this->getMockBuilder(Filesystem::class)
            ->getMock();
        $storageMock->method('exists')->willReturn(true);

        $expectedPoolFilePath = 'pool_downloads/10_28_2018-pool/list.txt';

        $parser = new DomainsFileParser($storageMock);
        $this->assertEquals($expectedPoolFilePath, $parser->findPoolFile($dirPath, $datetime));
    }

    public function testTodayPoolFileIsAbsent()
    {
        $dirPath = 'pool_downloads';
        $datetime = new \DateTime('2018-11-28');

        $storageMock = $this->getMockBuilder(Filesystem::class)
            ->getMock();

        $parser = new DomainsFileParser($storageMock);
        $this->assertEquals(false, $parser->findPoolFile($dirPath, $datetime));

    }
}