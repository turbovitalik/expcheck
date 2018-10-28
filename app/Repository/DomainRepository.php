<?php

namespace App\Repository;

use App\Entities\DomainName;

interface DomainRepository
{
    public function findAll();
    public function paginateAll($perPage = 15);
    public function findAllIndexedByName();
    public function findByName($name);
    public function save(DomainName $domainName);
    public function update(DomainName $domainName, $data);
    public function updateExpiresAt($id, \DateTime $expires);
}