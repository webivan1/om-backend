<?php

namespace App\Model\User\Repositories;

use App\Model\User\Entities\Role\Role;

class RoleRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByName(string $name): ?Role
    {
        return $this->findOneBy(['name' => $name]);
    }
}
