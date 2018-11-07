<?php

namespace Tests\Unit;

use App\Manager\DomainNameManager;
use Tests\TestCase;

class DomainNameManagerTest extends TestCase
{
    public function testCreateFromArray()
    {
        $domainName = 'abc.com';
        $attr = [
            'expiresAt' => new \DateTime(),
            'source' => 1,
            'wrong' => 'wrong',
        ];

        $manager = new DomainNameManager();
        $domainObject = $manager->createFromArray($domainName, $attr);

        $this->assertEquals($domainName, $domainObject->getName());
        $this->assertEquals($attr['expiresAt'], $domainObject->getExpiresAt());
        $this->assertObjectNotHasAttribute('wrong', $domainObject);
    }
}