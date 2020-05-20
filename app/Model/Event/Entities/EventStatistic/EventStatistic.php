<?php

namespace App\Model\Event\Entities\EventStatistic;

use App\Model\Common\Contracts\Arrayable;
use Doctrine\ORM\Mapping as ORM;
use App\Model\Event\Entities\Event\Event;

/**
 * @ORM\Entity(
 *     repositoryClass="App\Model\Event\Repositories\EventStatisticRepository"
 * )
 */
class EventStatistic implements Arrayable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected int $id;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="App\Model\Event\Entities\Event\Event",
     *     inversedBy="stat"
     * )
     */
    protected Event $event;

    /**
     * @ORM\Column(type="integer")
     */
    protected int $total;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    protected \DateTimeImmutable $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function setEvent(Event $event): void
    {
        $this->event = $event;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): void
    {
        $this->total = $total;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public static function create(Event $event, int $total): self
    {
        $model = new self;
        $model->setEvent($event);
        $model->setTotal($total);
        $model->setCreatedAt(new \DateTimeImmutable());

        return $model;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'total' => $this->total,
            'createdAt' => $this->createdAt->getTimestamp() * 1000
        ];
    }
}
