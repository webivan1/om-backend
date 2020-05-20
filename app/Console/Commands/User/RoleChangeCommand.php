<?php

namespace App\Console\Commands\User;

use App\Model\User\Entities\User\User;
use App\Model\User\UseCases\User\UserService;
use Illuminate\Console\Command;
use LaravelDoctrine\ORM\Facades\EntityManager;

class RoleChangeCommand extends Command
{
    protected $signature = 'user:change:role {userId} {roles}';
    protected $description = 'Change roles';

    public function handle(UserService $service)
    {
        $repo = EntityManager::getRepository(User::class);

        /** @var User $user */
        if ($user = $repo->find($this->argument('userId'))) {
            $roles = $service->getRoleCollections(explode(',', $this->argument('roles')));
            $user->setRoles($roles);

            EntityManager::persist($user);
            EntityManager::flush();
        } else {
            $this->error('User is not exists');
        }
    }
}
