<?php

namespace App\Manager;

use App\Entities\DomainName;
use App\Repository\DomainRepository;
use LaravelDoctrine\ORM\Facades\EntityManager;

class DomainNameManager
{
    /**
     * @var DomainRepository
     */
    private $domainRepository;

    /**
     * DomainNameManager constructor.
     * @param DomainRepository $domainRepository
     */
    public function __construct(DomainRepository $domainRepository)
    {
        $this->domainRepository = $domainRepository;
    }

    /**
     * @param $arrayData array
     * @return DomainName
     */
    public function createFromArray($arrayData)
    {
        $domain = new DomainName();

        $domain->setName($arrayData['name']);
        $domain->setExpiresAt($arrayData['expiresAt']);

        return $domain;
    }

    public function save(DomainName $domainName)
    {
        $oldDomain = $this->domainRepository->findByName($domainName->getName());

        if ($oldDomain) {
            $this->update($domainName, $oldDomain);
        } else {
            $this->insert();
        }
    }

    public function update(DomainName $domainName, DomainName $existedName)
    {
        $existedName->setExpiresAt($domainName->getExpiresAt());

        EntityManager::persist($existedName);
        EntityManager::flush();
    }
}