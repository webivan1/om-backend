<?php

namespace App\Model\User\UseCases\User;

class UserUpdateDto
{
    public ?string $name;
    public ?string $email;
    public ?string $password;
    public ?string $status;
    public ?bool $isVerified;
    public ?array $roles = [];
}
