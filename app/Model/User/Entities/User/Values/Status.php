<?php

namespace App\Model\User\Entities\User\Values;

use App\Model\Common\Contracts\ValueContract;
use App\Model\User\Entities\User\User;
use Webmozart\Assert\Assert;

class Status implements ValueContract
{
    private ?string $value;

    public function __construct(string $value)
    {
        Assert::inArray($value, [
            User::STATUS_REJECT,
            User::STATUS_ACTIVE,
            User::STATUS_WAIT
        ]);

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
