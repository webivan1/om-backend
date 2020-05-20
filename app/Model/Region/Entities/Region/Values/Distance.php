<?php

namespace App\Model\Region\Entities\Region\Values;

use App\Model\Common\Contracts\ValueContract;
use Webmozart\Assert\Assert;

class Distance implements ValueContract
{
    private int $value;

    public function __construct(int $value)
    {
        Assert::notEmpty($value);

        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
