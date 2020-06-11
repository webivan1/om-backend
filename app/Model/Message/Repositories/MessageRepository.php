<?php

namespace App\Model\Message\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use LaravelDoctrine\ORM\Pagination\PaginatesFromRequest;

class MessageRepository extends \Doctrine\ORM\EntityRepository
{
    use PaginatesFromRequest;

    public function listForChat(int $eventId, int $limit = 40): LengthAwarePaginator
    {
        $builder = $this->createQueryBuilder('e');
        $builder->andWhere('e.event = :eventId')->setParameter('eventId', $eventId);
        $builder->orderBy('e.createdAt', 'desc');

        return $this->paginate($builder->getQuery(), $limit);
    }
}
