<?php

namespace App\Manager;

use App\Entities\DomainName;
use http\Exception\InvalidArgumentException;
use LaravelDoctrine\ORM\Facades\EntityManager;

class DomainNameManager
{
    /**
     * @param $name
     * @param array $attr
     * @return DomainName
     * @throws \Exception
     */
    public function createFromArray(string $name, $attr = [])
    {
        $domain = new DomainName($name);

        foreach ($attr as $key => $value) {
            $setter = 'set' . ucfirst($key);
            if (method_exists($domain, $setter)) {
                $domain->{$setter}($value);
            }
        }

        return $domain;
    }

    public function update(DomainName $domainName, DomainName $existedName)
    {
        $existedName->setExpiresAt($domainName->getExpiresAt());

        EntityManager::persist($existedName);
        EntityManager::flush();
    }
}