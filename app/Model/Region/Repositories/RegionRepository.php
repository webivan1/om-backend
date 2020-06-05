<?php

namespace App\Model\Region\Repositories;

use App\Model\Region\Entities\Region\Region;

class RegionRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllArray(): array
    {
        return array_map(
            fn(Region $region): array => $region->toArray(),
            $this->findAll()
        );
    }

    public function getOne(int $id): Region
    {
        /** @var Region|null $model */
        $model = $this->find($id);

        if (empty($model)) {
            throw new \DomainException('This region is not found');
        }

        return $model;
    }
}
