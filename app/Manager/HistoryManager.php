<?php

namespace App\Manager;

use App\Entities\History;
use Doctrine\ORM\EntityManagerInterface;

class HistoryManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * HistoryManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createHistoryRecord($fileName, $status, $description)
    {
        $record = new History();
        $record->setFileName($fileName)->setStatus($status)->setDescription($description);

        return $record;
    }

    public function save(History $history)
    {
        $this->em->persist($history);
        $this->em->flush();
    }
}