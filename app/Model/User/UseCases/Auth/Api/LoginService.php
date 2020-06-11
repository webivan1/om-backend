<?php

namespace App\Model\User\UseCases\Auth\Api;

use App\Model\User\Entities\User\User;
use App\Model\User\Repositories\UserRepository;
use App\Model\User\Services\PasswordHash;
use App\Model\User\UseCases\User\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class LoginService
{
    private EntityManagerInterface $em;

    /** @var ObjectRepository|UserRepository */
    private ObjectRepository $repo;

    private UserService $userService;

    public function __construct(EntityManagerInterface $em, UserService $userService)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(User::class);
        $this->userService = $userService;
    }

    public function getUserByCredentials(string $email, string $password): User
    {
        if (!$user = $this->repo->findByEmail($email)) {
            throw new \DomainException('This is not registered');
        }

        if (!PasswordHash::equal($password, $user->getPassword())) {
            throw new \DomainException('Your password is not correct');
        }

        $this->userService->checkUserStatuses($user);

        return $user;
    }
}
