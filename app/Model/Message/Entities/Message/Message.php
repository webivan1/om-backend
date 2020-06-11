<?php

namespace App\Model\Message\Entities\Message;

use App\Model\Common\Contracts\Arrayable;
use Doctrine\ORM\Mapping as ORM;
use App\Model\Donation\Entities\Donation\Donation;
use App\Model\Event\Entities\Event\Event;
use App\Model\Message\Entities\Message\Values\MessageText;
use App\Model\User\Entities\User\User;

/**
 * @ORM\Entity(
 *     repositoryClass="App\Model\Message\Repositories\MessageRepository"
 * )
 * @ORM\Table(
 *     indexes={
 *         @ORM\Index(name="created_at_idx", columns={"created_at"})
 *     }
 * )
 */
class Message implements Arrayable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected int $id;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="App\Model\Event\Entities\Event\Event"
     * )
     */
    protected Event $event;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="App\Model\User\Entities\User\User"
     * )
     */
    protected ?User $user = null;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="App\Model\Donation\Entities\Donation\Donation"
     * )
     */
    protected ?Donation $donation = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $message;

    /**
     * @ORM\Column(type="boolean")
     */
    protected bool $organizer = false;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function isOrganizer(): bool
    {
        return $this->organizer;
    }

    public function setOrganizer(bool $organizer): void
    {
        $this->organizer = $organizer;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getDonation(): ?Donation
    {
        return $this->donation;
    }

    public function setDonation(?Donation $donation): void
    {
        $this->donation = $donation;
    }

    public static function create(
        Event $event,
        MessageText $text,
        bool $organizer,
        ?User $user,
        ?Donation $donation
    ): self
    {
        $model = new self;
        $model->setEvent($event);
        $model->setMessage($text->getValue());
        $model->setOrganizer($organizer);
        !$user ?: $model->setUser($user);
        !$donation ?: $model->setDonation($donation);
        $model->setCreatedAt(new \DateTimeImmutable());

        return $model;
    }

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'organizer' => $this->organizer,
            'event' => $this->event->toArray(),
            'user' => $this->user ? [
                'id' => $this->user->getId(),
                'name' => $this->user->getName(),
            ] : null,
            'donation' => $this->donation ? [
                'name' => $this->donation->getUsername(),
            ] : null,
            'createdAt' => $this->createdAt
                ->setTimezone(new \DateTimeZone($this->event->getRegion()->getTimezone()))
                ->getTimestamp() * 1000
        ];
    }
}
