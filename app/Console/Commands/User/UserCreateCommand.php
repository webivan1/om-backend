<?php

namespace App\Console\Commands\User;

use App\Model\User\UseCases\User\UserCreateDto;
use App\Model\User\UseCases\User\UserService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class UserCreateCommand extends Command
{
    protected $signature = 'user:create {name} {email} {verified} {status} {role} {password}';
    protected $description = 'Create new user';

    public function handle(UserService $service)
    {
        try {
            $dto = new UserCreateDto();
            $dto->name = (string) $this->argument('name');
            $dto->email = (string) $this->argument('email');
            $dto->status = (string) $this->argument('status');
            $dto->isVerified = (bool) $this->argument('verified');
            $dto->password = $this->argument('password') ?? Str::random(8);

            $roles = explode(',', $this->argument('role'));
            $roles = array_filter($roles);

            $dto->roles = $roles;

            $service->create($dto);

            $this->alert('User has been created');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
