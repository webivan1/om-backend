<?php

namespace App\Model\Event\Entities\Event;

use App\Model\Common\Services\DateIntervalService;
use App\Model\Event\Entities\Event\Values\Description;
use App\Model\Event\Entities\Event\Values\EventDate;
use App\Model\Event\Entities\Event\Values\Status;
use App\Model\Event\Entities\Event\Values\Title;
use Doctrine\ORM\Mapping as ORM;
use App\Model\Common\Contracts\Arrayable;
use App\Model\Region\Entities\Region\Region;
use App\Model\User\Entities\User\User;

/**
 * @ORM\Entity(
 *     repositoryClass="App\Model\Event\Repositories\EventRepository"
 * )
 * @ORM\Table(
 *     indexes={
 *         @ORM\Index(name="status_idx", columns={"status"}),
 *         @ORM\Index(name="start_at_idx", columns={"start_at"}),
 *         @ORM\Index(name="finish_at_idx", columns={"finish_at"}),
 *     }
 * )
 */
class Event implements Arrayable
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_MODERATION = 'moderation';
    public const STATUS_REJECT = 'reject';
    public const STATUS_APPROVED = 'approved';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected int $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    protected string $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $description;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="App\Model\Region\Entities\Region\Region",
     *     inversedBy="event"
     * )
     */
    protected Region $region;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="App\Model\User\Entities\User\User",
     *     inversedBy="event"
     * )
     */
    protected User $user;

    /**
     * @ORM\Column(type="datetime")
     */
    protected \DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    protected \DateTime $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    protected \DateTime $startAt;

    /**
     * @ORM\Column(type="datetime")
     */
    protected \DateTime $finishAt;

    /**
     * @ORM\Column(type="string", length=16)
     */
    protected string $status;

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isModeration(): bool
    {
        return $this->status === self::STATUS_MODERATION;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECT;
    }

    public function toApprove()
    {
        $this->setStatus(self::STATUS_APPROVED);
    }

    public function toReject()
    {
        $this->setStatus(self::STATUS_REJECT);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getRegion(): Region
    {
        return $this->region;
    }

    public function setRegion(Region $region): void
    {
        $this->region = $region;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getStartAt(): \DateTime
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTime $startAt): void
    {
        $this->startAt = $startAt;
    }

    public function getFinishAt(): \DateTime
    {
        return $this->finishAt;
    }

    public function setFinishAt(\DateTime $finishAt): void
    {
        $this->finishAt = $finishAt;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function isStartedEvent(): bool
    {
        return $this->startAt <= new \DateTime() && !$this->isFinishedEvent();
    }

    public function isFinishedEvent(): bool
    {
        return $this->finishAt < new \DateTime();
    }

    public static function create(User $user, Region $region, Title $title, Description $description, EventDate $date): self
    {
        $model = new self;
        $model->setUser($user);
        $model->setRegion($region);
        $model->setTitle($title->getValue());
        $model->setDescription($description->getValue());
        $model->setCreatedAt(new \DateTime());
        $model->setUpdatedAt(new \DateTime());

        [$startAt, $finishAt] = $date->getValue();

        $model->setStartAt($startAt);
        $model->setFinishAt($finishAt);
        $model->setStatus(self::STATUS_MODERATION);

        return $model;
    }

    public function update(Title $title, Description $description, EventDate $date): void
    {
        $this->setTitle($title->getValue());
        $this->setDescription($description->getValue());

        [, $finishAt] = $date->getValue();

        $this->setFinishAt($finishAt);
        $this->setStatus(self::STATUS_MODERATION);
    }

    public static function changeStatus(Event $event, Status $status): self
    {
        $event->setStatus($status->getValue());
        return $event;
    }

    public function toArray(): array
    {
        $startAt = clone $this->startAt;
        $finishAt = clone $this->finishAt;

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'region' => $this->region->toArray(),
            'status' => $this->status,
            'isStarted' => $this->isStartedEvent(),
            'isFinished' => $this->isFinishedEvent(),
            'user' => $this->user->toArray(),
            'createdAt' => $this->createdAt->getTimestamp() * 1000,
            'startAt' => $startAt
                ->setTimezone(new \DateTimeZone($this->region->getTimezone()))
                ->getTimestamp() * 1000,
            'finishAt' => $finishAt
                ->setTimezone(new \DateTimeZone($this->region->getTimezone()))
                ->getTimestamp() * 1000,
            'interval' => DateIntervalService::getHours($startAt, $finishAt),
            'timezoneUTC' => $startAt->format('P')
        ];
    }
}
