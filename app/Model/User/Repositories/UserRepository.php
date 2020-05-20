<?php

namespace App\Model\User\Repositories;

use App\Model\User\Entities\User\User;

class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }
}
