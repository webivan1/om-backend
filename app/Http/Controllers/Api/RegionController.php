<?php

namespace App\Http\Controllers\Api;

use App\Model\Region\Entities\Region\Region;
use App\Model\Region\Repositories\RegionRepository;
use LaravelDoctrine\ORM\Facades\EntityManager;

class RegionController
{
    public function list()
    {
        /** @var RegionRepository $repository */
        $repository = EntityManager::getRepository(Region::class);

        return $repository->findAllArray();
    }
}
