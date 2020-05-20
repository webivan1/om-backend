<?php

namespace App\Model\User\Entities\User\Values;

use App\Model\Common\Contracts\ValueContract;

class IsVerified implements ValueContract
{
    private bool $value;

    public function __construct(bool $value)
    {
        $this->value = $value;
    }

    public function getValue(): bool
    {
        return $this->value;
    }
}
