<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class HistoryRepository
{
    /**
     * @var EntityRepository
     */
    protected $entityRepository;

    /**
     * HistoryRepository constructor.
     * @param EntityRepository $entityRepository
     */
    public function __construct(EntityRepository $entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }

    /**
     * @return array
     */
    public function findAll()
    {
        return $this->entityRepository->findAll();
    }

    /**
     * @param array $criteria
     * @param null $orderBy
     * @param null $limit
     * @param null $offset
     * @return array
     */
    public function findBy($criteria = [], $orderBy = null, $limit = null, $offset = null)
    {
        return $this->entityRepository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param array $criteria
     * @return null|object
     */
    public function findOneBy($criteria = [])
    {
        return $this->entityRepository->findOneBy($criteria);
    }
}