<?php

namespace App\Model\Donation\Entities\Donation;

use App\Model\Common\Contracts\Arrayable;
use Doctrine\ORM\Mapping as ORM;
use App\Model\Donation\Entities\Donation\Values\Amount;
use App\Model\Donation\Entities\Donation\Values\Email;
use App\Model\Donation\Entities\Donation\Values\MessageText;
use App\Model\Donation\Entities\Donation\Values\Source;
use App\Model\Donation\Entities\Donation\Values\Username;
use App\Model\Event\Entities\Event\Event;

/**
 * @ORM\Entity(
 *     repositoryClass="App\Model\Donation\Repositories\DonationRepository"
 * )
 * @ORM\Table(
 *     indexes={
 *         @ORM\Index(name="created_at_idx", columns={"created_at"}),
 *         @ORM\Index(name="status_idx", columns={"status"}),
 *         @ORM\Index(name="source_idx", columns={"source"})
 *     }
 * )
 */
class Donation implements Arrayable
{
    public const STATUS_WAITING = 'waiting';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_APPROVED = 'approved';

    public const SOURCE_PAYPAL = 'paypal';
    public const SOURCE_TINKOFF = 'tinkoff';

    public const MIN_PRICE = 50;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected int $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected string $username;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected ?string $email = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected ?string $message = null;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    protected float $amount;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="App\Model\Event\Entities\Event\Event"
     * )
     */
    protected ?Event $event;

    /**
     * @ORM\Column(type="string", length=16)
     */
    protected string $status;

    /**
     * @ORM\Column(type="string", length=16)
     */
    protected string $source;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    protected \DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    protected \DateTimeImmutable $updatedAt;

    public function isPaypal(): bool
    {
        return $this->source === self::SOURCE_PAYPAL;
    }

    public function isTinkoff(): bool
    {
        return $this->source === self::SOURCE_TINKOFF;
    }

    public function isWaiting(): bool
    {
        return $this->status === self::STATUS_WAITING;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): void
    {
        $this->event = $event;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function setSource(string $source): void
    {
        $this->source = $source;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public static function new(
        ?Event $event,
        Amount $amount,
        Username $username,
        ?Email $email,
        ?MessageText $message,
        Source $source
    ): self
    {
        $model = new self;
        !$event ?: $model->setEvent($event);
        $model->setAmount($amount->getValue());
        $model->setUsername($username->getValue());
        !$email ?: $model->setEmail($email->getValue());
        !$message ?: $model->setMessage($message->getValue());
        $model->setSource($source->getValue());
        $model->setCreatedAt(new \DateTimeImmutable());
        $model->setUpdatedAt(new \DateTimeImmutable());
        $model->setStatus(self::STATUS_WAITING);

        return $model;
    }

    public function toReject(): void
    {
        $this->setStatus(self::STATUS_REJECTED);
    }

    public function toApprove(): void
    {
        $this->setStatus(self::STATUS_APPROVED);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'source' => $this->source,
            'status' => $this->status,
            'amount' => $this->amount,
        ];
    }
}
