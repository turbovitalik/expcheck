<?php

namespace Tests\Unit;

use App\Console\Commands\ExportDomainsPool;
use App\Utils\DomainsFileParser;
use Tests\TestCase;

class ExportDomainCommandTest extends TestCase
{
    public function testExtractTldFromDomainName()
    {
        $domainName = 'abc-test.com';
        $tldExpected = 'com';

        /** @var DomainsFileParser $parserMock */
        $parserMock = $this->createMock(DomainsFileParser::class);

        $command = new ExportDomainsPool($parserMock);
        $this->assertEquals($tldExpected, $command->getTld($domainName));
    }
}