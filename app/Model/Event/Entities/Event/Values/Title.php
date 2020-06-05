<?php

namespace App\Model\Event\Entities\Event\Values;

use App\Model\Common\Contracts\ValueContract;
use Webmozart\Assert\Assert;

class Title implements ValueContract
{
    private $value;

    public function __construct(string $value)
    {
        $this->value = htmlspecialchars(strip_tags(trim($value)));

        Assert::notEmpty($this->value);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
