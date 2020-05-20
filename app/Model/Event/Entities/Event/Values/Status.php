<?php

namespace App\Model\Event\Entities\Event\Values;

use App\Model\Common\Contracts\ValueContract;
use App\Model\Event\Entities\Event\Event;
use Webmozart\Assert\Assert;

class Status implements ValueContract
{
    private string $value;

    public function __construct(string $value)
    {
        Assert::inArray($value, [
            Event::STATUS_DRAFT,
            Event::STATUS_MODERATION,
            Event::STATUS_REJECT,
            Event::STATUS_APPROVED
        ]);

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
