<?php

namespace App\Model\Event\Entities\Event\Values;

use App\Model\Common\Contracts\ValueContract;
use Webmozart\Assert\Assert;

class EventDate implements ValueContract
{
    private \DateTime $startAt;
    private \DateTime $finishAt;

    public function __construct(string $startAt, string $timeZone, string $interval)
    {
        Assert::notEmpty($startAt);
        Assert::notEmpty($timeZone);
        Assert::notEmpty($interval);

        if (strtotime($startAt) === false) {
            throw new \DomainException('Start at have no correct format');
        }

        if (!preg_match('/^\+\s?\d+\s(minute|hour|day|week|month|year)s?$/', $interval)) {
            throw new \DomainException('This interval is not correct, format will be +3 hours');
        }

        $clientStartEvent = new \DateTime($startAt, new \DateTimeZone($timeZone));
        $this->startAt = $clientStartEvent->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        $this->finishAt = (clone $clientStartEvent)->modify($interval);
    }

    /**
     * @return \DateTime[]
     */
    public function getValue(): array
    {
        return [$this->startAt, $this->finishAt];
    }
}
