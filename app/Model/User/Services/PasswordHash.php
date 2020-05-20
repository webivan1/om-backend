<?php

namespace App\Model\User\Services;

use Illuminate\Support\Facades\Hash;

class PasswordHash
{
    public static function encode(string $password): string
    {
        return Hash::make($password);
    }

    public static function equal(string $password, string $hash): bool
    {
        return Hash::check($password, $hash);
    }
}
