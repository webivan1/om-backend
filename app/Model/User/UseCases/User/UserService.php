<?php

namespace App\Model\User\UseCases\User;

use App\Model\Common\Contracts\TokenizerContract;
use App\Model\User\Entities\Role\Role;
use App\Model\User\Entities\User\User;
use App\Model\User\Repositories\RoleRepository;
use App\Model\User\Repositories\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

// Events
use App\Events\User\UserCreatedEvent;
use App\Events\User\UserDeletedEvent;
use App\Events\User\UserUpdatedEvent;

// Values
use App\Model\User\Entities\User\Values\Email;
use App\Model\User\Entities\User\Values\Id;
use App\Model\User\Entities\User\Values\IsVerified;
use App\Model\User\Entities\User\Values\Name;
use App\Model\User\Entities\User\Values\Password;
use App\Model\User\Entities\User\Values\Status;

class UserService
{
    private TokenizerContract $tokenizer;
    private EntityManagerInterface $em;

    /**
     * @var UserRepository $repository
     */
    private ObjectRepository $repository;

    /**
     * @var RoleRepository
     */
    private ObjectRepository $roleRepository;

    public function __construct(TokenizerContract $tokenizer, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repository = $this->em->getRepository(User::class);
        $this->roleRepository = $this->em->getRepository(Role::class);
        $this->tokenizer = $tokenizer;
    }

    public function getRoleCollections(array $names): array
    {
        $roles = array_map(
            fn(string $name): ?Role => $this->roleRepository->findByName($name),
            $names
        );

        return array_filter($roles);
    }

    public function create(UserCreateDto $dto): User
    {
        if ($this->repository->findByEmail($dto->email)) {
            throw new \DomainException('This user is already exists');
        }

        $user = User::create(
            new Id($id = $this->tokenizer->generateRandomString()),
            new Name($dto->name),
            new Email($dto->email),
            new Password($dto->password),
            new IsVerified($dto->isVerified),
            $dto->status ? new Status($dto->status) : null,
            $this->getRoleCollections($dto->roles)
        );

        $this->em->persist($user);
        $this->em->flush();

        event(new UserCreatedEvent($user));

        return $user;
    }

    public function update(User $user, UserUpdateDto $dto)
    {
        $user->update(
            $dto->name ? new Name($dto->name) : null,
            $dto->email ? new Email($dto->email) : null,
            $dto->password ? new Password($dto->password) : null,
            $dto->isVerified === null ? null : new IsVerified($dto->isVerified),
            $dto->status ? new Status($dto->status) : null,
            $this->getRoleCollections($dto->roles)
        );

        $this->em->persist($user);
        $this->em->flush();

        event(new UserUpdatedEvent($user));
    }

    // @todo Передавать Id или Email искать сразу в методе юзера и удалять его
    public function delete(User $user)
    {
        event(new UserDeletedEvent($user));

        $this->em->remove($user);
        $this->em->flush();
    }

    public function checkUserStatuses(User $user): void
    {
        if ($user->isWait()) {
            throw new \DomainException('Your account is not activated');
        }

        if ($user->isReject()) {
            throw new \DomainException($user->getRejectReason());
        }

        if (!$user->isActive()) {
            throw new \DomainException('Error logged in');
        }
    }
}
