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
}
