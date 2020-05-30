<?php

use Illuminate\Database\Seeder;
use App\Model\User\Entities\User\User;
use App\Model\User\UseCases\User\UserService;
use App\Model\User\UseCases\User\UserCreateDto;

class UserSeeder extends Seeder
{
    private UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function run()
    {
        $dto = new UserCreateDto();
        $dto->email = 'admin@admin.ru';
        $dto->name = 'admin';
        $dto->password = '12345';
        $dto->isVerified = true;
        $dto->status = User::STATUS_ACTIVE;
        $dto->roles = ['admin'];

        $this->service->create($dto);
    }
}
