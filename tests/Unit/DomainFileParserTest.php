<?php

namespace Tests\Unit;

use App\Utils\DomainsFileParser;
use Tests\TestCase;

class DomainFileParserTest extends TestCase
{
//    public function testLineIsParsedSuccessfully()
//    {
//        $validLine = "puchd.ac,10/25/2018 12:40:00 PM,AUC\n";
//        $expectedArray = [
//            'name' => 'puchd.ac',
//            'expiresAt' => '10/25/2018 12:40:00 PM',
//        ];
//
//        $parser = new DomainsFileParser();
//        $this->assertEquals($expectedArray, $parser->parseLine($validLine));
//    }

    public function testTodayPoolFileExists()
    {
        $dirPath = __DIR__ . '/files';
        $datetime = new \DateTime('2018-10-28');

        $expectedPoolFilePath = __DIR__ . '/files/10_28_2018-pool/list.txt';

        $parser = new DomainsFileParser();
        $this->assertEquals($expectedPoolFilePath, $parser->findPoolFile($dirPath, $datetime));
    }

    public function testTodayPoolFileIsAbsent()
    {
        $dirPath = __DIR__ . '/files';
        $datetime = new \DateTime('2018-11-28');

        $parser = new DomainsFileParser();
        $this->assertEquals(false, $parser->findPoolFile($dirPath, $datetime));

    }
}