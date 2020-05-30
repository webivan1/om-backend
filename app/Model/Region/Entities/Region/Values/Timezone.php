<?php

namespace App\Model\Region\Entities\Region\Values;

use App\Model\Common\Contracts\ValueContract;
use Webmozart\Assert\Assert;

class Timezone implements ValueContract
{
    private string $value;

    public function __construct(string $value)
    {
//        Assert::inArray($value, array_column(\DateTimeZone::listAbbreviations(), 'timezone_id'));
        Assert::notEmpty($value);

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
