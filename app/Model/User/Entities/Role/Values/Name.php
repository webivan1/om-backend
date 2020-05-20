<?php

namespace App\Model\User\Entities\Role\Values;

use App\Model\Common\Contracts\ValueContract;
use Webmozart\Assert\Assert;

class Name implements ValueContract
{
    private string $value;

    public function __construct(string $value)
    {
        Assert::regex($value, '/^[a-z]+$/');
        Assert::minLength($value, 3);
        Assert::maxLength($value, 50);

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
