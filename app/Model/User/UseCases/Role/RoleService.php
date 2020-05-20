<?php

namespace App\Model\User\UseCases\Role;

use App\Model\User\Entities\Role\Role;
use App\Model\User\Entities\Role\Values\Name;
use App\Model\User\Repositories\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class RoleService
{
    /**
     * @var RoleRepository $repository
     */
    private EntityRepository $repository;
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(Role::class);
    }

    public function create(string $name): Role
    {
        if ($this->repository->findByName($name)) {
            throw new \DomainException('This role is already exists');
        }

        $role = Role::create(new Name($name));

        $this->em->persist($role);
        $this->em->flush();

        return $role;
    }

    public function delete(string $name): void
    {
        if (!$role = $this->repository->findByName($name)) {
            throw new \DomainException('This role is not found');
        }

        $this->em->remove($role);
        $this->em->flush();
    }
}
