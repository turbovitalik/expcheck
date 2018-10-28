<?php

namespace App\Repository;

use App\Entities\DomainName;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use LaravelDoctrine\ORM\Pagination\PaginatesFromRequest;

class DoctrineDomainRepository implements DomainRepository
{
    use PaginatesFromRequest;

    /**
     * @var EntityRepository
     */
    protected $genericRepository;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * DoctrineDomainRepository constructor.
     * @param EntityRepository $genericRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityRepository $genericRepository, EntityManagerInterface $entityManager)
    {
        $this->genericRepository = $genericRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @return object
     */
    public function findAll()
    {
        return $this->genericRepository->findAll();
    }

    /**
     * @param $name string
     * @return null|object
     */
    public function findByName($name)
    {
        return $this->genericRepository->findOneBy(['name' => $name]);
    }

    public function update(DomainName $domain, $data)
    {
        foreach ($data as $key => $value) {
            $setMethod = 'set' . ucfirst($key);
            if (!method_exists($domain, $setMethod)) {
                continue;
            }
            $domain->{$setMethod}($value);
        }

        $this->save($domain);
    }

    public function save(DomainName $domain)
    {
        $this->entityManager->persist($domain);
        $this->entityManager->flush();
    }

    public function findAllIndexedByName()
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('d');
        $qb->from(DomainName::class, 'd', 'd.name');
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    public function updateExpiresAt($id, \DateTime $expires)
    {
        $expiresStr = $expires->format('Y-m-d H:i:s');

        $qb = $this->entityManager->createQueryBuilder();
        $q = $qb->update(DomainName::class, 'd')
            ->set('d.expiresAt', $qb->expr()->literal($expiresStr))
            ->where('d.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        $q->execute();
    }

    public function createQueryBuilder($alias, $indexBy = null)
    {
        return $this->genericRepository->createQueryBuilder($alias);
    }
}