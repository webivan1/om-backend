<?php

namespace App\Model\Event\Repositories;

use App\Model\Event\Entities\Event\Event;
use App\Model\User\Entities\User\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use LaravelDoctrine\ORM\Pagination\PaginatesFromRequest;

class EventRepository extends \Doctrine\ORM\EntityRepository
{
    use PaginatesFromRequest;

    public function listByUser(User $user, int $limit = 10, array $filters): LengthAwarePaginator
    {
        $builder = $this->createQueryBuilder('e');
        $builder->where('e.user = :user')->setParameter('user', $user->getId());
        $builder->orderBy('e.updatedAt', 'desc');

        if (!empty($filters['region'])) {
            $builder->andWhere('e.region = :region')
                ->setParameter('region', $filters['region']);
        }

        if (!empty($filters['status'])) {
            $builder->andWhere('e.status = :status')
                ->setParameter('status', $filters['status']);
        }

        if (!empty($filters['title'])) {
            $builder->andWhere('e.title LIKE :title')
                ->setParameter('title', "%{$filters['title']}%");
        }

        if (!empty($filters['startDate'])) {
            $builder->andWhere('DATE(e.startAt) = :startAt')
                ->setParameter('startAt', $filters['startDate']);
        }

        return $this->paginate($builder->getQuery(), $limit);
    }

    public function publicList(array $params = [], int $limit = 12): LengthAwarePaginator
    {
        $builder = $this->createQueryBuilder('e');
        $builder->where('e.status = :status');

        $builder->addSelect('IFELSE (NOW() > e.startAt AND NOW() < e.finishAt, 1, 0) AS online');
        $builder->addSelect('IFELSE (NOW() < e.startAt, 1, 0) AS isNotStarting');

        if (!empty($params['region'])) {
            $builder->andWhere('e.region = :region');
            $builder->setParameter('region', $params['region']);
        }

        $builder->setParameter('status', Event::STATUS_APPROVED);

        $builder->orderBy('online', 'desc');
        $builder->addOrderBy('isNotStarting', 'desc');
        $builder->addOrderBy('e.startAt', 'asc');

        return $this->paginate($builder->getQuery(), $limit);
    }

    public function getDetail(int $id): Event
    {
        $result = $this->findOneBy([
            'id' => $id,
            'status' => Event::STATUS_APPROVED
        ]);

        if ($result === null) {
            throw new \DomainException('This event is not found');
        }

        return $result;
    }

    public function getById(int $id): Event
    {
        $result = $this->find($id);

        if ($result === null) {
            throw new \DomainException('This event is not found');
        }

        return $result;
    }
}
