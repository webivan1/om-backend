<?php

namespace App\Model\Token\Entities\Token;

use App\Model\Token\Entities\Token\Values\Uuid;
use App\Model\User\Entities\User\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(
 *     repositoryClass="App\Model\Token\Repositories\TokenRepository"
 * )
 */
class Token
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=150)
     */
    protected string $token;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="App\Model\User\Entities\User\User",
     *     inversedBy="token"
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
    protected \DateTime $expiredAt;

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
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

    public function getExpiredAt(): \DateTime
    {
        return $this->expiredAt;
    }

    public function setExpiredAt(\DateTime $expiredAt): void
    {
        $this->expiredAt = $expiredAt;
    }

    public function isExpire(): bool
    {
        return $this->expiredAt < new \DateTime();
    }

    public static function create(User $user, Uuid $uuid): self
    {
        $model = new self;
        $model->createdAt = $current = new \DateTime();
        $model->expiredAt = (clone $current)->modify('+ 1 day');
        $model->setUser($user);
        $model->setToken($uuid->getValue());

        return $model;
    }
}
