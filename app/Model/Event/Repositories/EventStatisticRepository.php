<?php

namespace App\Model\Event\Repositories;

class EventStatisticRepository extends \Doctrine\ORM\EntityRepository
{
    public function findList(int $eventId): array
    {
        return $this->findBy(['event' => $eventId], ['createdAt' => 'asc']);
    }
}
