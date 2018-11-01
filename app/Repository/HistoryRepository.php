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
}