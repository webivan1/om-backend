<?php

namespace App\Model\User\Entities\User;

use App\Model\Common\Contracts\Arrayable;
use App\Model\Event\Entities\Event\Event;
use App\Model\User\Entities\Role\Role;
use App\Model\User\Services\PasswordHash;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\ACL\Mappings as ACL;
use App\Model\Token\Entities\Token\Token;

// Traits
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Notifications\Notifiable;
use LaravelDoctrine\ACL\Roles\HasRoles;
use Illuminate\Auth\Authenticatable;

// Contracts
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use LaravelDoctrine\ACL\Contracts\HasRoles as HasRolesContract;

// Values
use App\Model\User\Entities\User\Values\Email;
use App\Model\User\Entities\User\Values\Id;
use App\Model\User\Entities\User\Values\Name;
use App\Model\User\Entities\User\Values\Password;
use App\Model\User\Entities\User\Values\IsVerified;
use App\Model\User\Entities\User\Values\Status;

/**
 * @ORM\Entity(
 *     repositoryClass="App\Model\User\Repositories\UserRepository"
 * )
 * @ORM\Table(
 *     uniqueConstraints={@ORM\UniqueConstraint(name="email_idx", columns={"email"})},
 *     indexes={
 *         @ORM\Index(name="status_idx", columns={"status"})
 *     }
 * )
 */
class User implements HasRolesContract, AuthenticatableContract, CanResetPasswordContract, Arrayable
{
    use HasRoles, Notifiable, CanResetPassword, Authenticatable;

    public const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_REJECT = 'reject';

    /**
     * @ORM\Column(type="string", length=50)
     * @ORM\Id
     */
    protected string $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected string $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected string $email;

    /**
     * @ORM\Column(type="string", length=150)
     */
    protected string $password;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected ?\DateTime $emailVerifiedAt;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected string $rememberToken;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    protected ?string $rejectReason;

    /**
     * @ORM\Column(type="string", length=16)
     */
    protected string $status;

    /**
     * @ORM\Column(type="datetime")
     */
    protected \DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    protected \DateTime $updatedAt;

    /**
     * @ORM\OneToOne(
     *     targetEntity="App\Model\Token\Entities\Token\Token",
     *     mappedBy="user"
     * )
     * @ORM\OrderBy({"created_at" = "DESC"})
     */
    protected ?Token $token;

    /**
     * @ACL\HasRoles()
     * @var \Doctrine\Common\Collections\ArrayCollection|\LaravelDoctrine\ACL\Contracts\Role[]
     */
    protected $roles;

    protected ?string $originPassword;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param Role[] $roles
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    public function getKeyName(): string
    {
        return 'id';
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isReject(): bool
    {
        return $this->status === self::STATUS_REJECT;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRejectReason(): ?string
    {
        return $this->rejectReason;
    }

    public function setRejectReason(?string $rejectReason): void
    {
        $this->rejectReason = $rejectReason;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
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

    public function getEmailVerifiedAt(): ?\DateTime
    {
        return $this->emailVerifiedAt;
    }

    public function setEmailVerifiedAt(?\DateTime $emailVerifiedAt): void
    {
        $this->emailVerifiedAt = $emailVerifiedAt;
    }

    public function getOriginPassword(): ?string
    {
        return $this->originPassword;
    }

    public function getToken(): ?Token
    {
        return $this->token;
    }

    public function getDefaultStatus(): string
    {
        return self::STATUS_WAIT;
    }

    public static function create(
        Id $id,
        Name $name,
        Email $email,
        Password $password,
        IsVerified $isVerified,
        ?Status $status = null,
        ?array $roles = []
    ): self
    {
        $user = new User();
        $user->setId($id->getValue());
        $user->setName($name->getValue());
        $user->setEmail($email->getValue());
        $user->setPassword(PasswordHash::encode($password->getValue()));
        $user->setStatus($status ? $status->getValue() : $user->getDefaultStatus());
        $user->setEmailVerifiedAt($isVerified->getValue() ? new \DateTime() : null);
        $user->setCreatedAt(new \DateTime());
        $user->setUpdatedAt(new \DateTime());

        array_map(fn(Role $role): bool => $user->roles->add($role), $roles);

        $user->originPassword = $password->getValue();

        return $user;
    }

    public function update(
        ?Name $name,
        ?Email $email,
        ?Password $password,
        ?IsVerified $isVerified,
        ?Status $status = null,
        ?array $roles = []
    ): void
    {
        !$name ?: $this->setName($name->getValue());
        !$status ?: $this->setStatus($status->getValue());

        if ($password) {
            $this->setPassword(PasswordHash::encode($password->getValue()));
            $this->originPassword = $password->getValue();
        }

        $isVerified === null
            ?: $this->setEmailVerifiedAt($isVerified->getValue() ? new \DateTime() : null);

        if ($email) {
            $this->setEmail($email->getValue());
            $this->setEmailVerifiedAt(null);
        }

        if (!empty($roles)) {
            $this->roles->clear();
            array_map(fn(Role $role): bool => $this->roles->add($role), $roles);
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'isVerified' => (bool) $this->emailVerifiedAt,
            'roles' => array_column(
                array_map(fn(Role $role) => $role->toArray(), $this->roles->toArray()),
                'name'
            )
        ];
    }
}
