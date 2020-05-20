<?php

namespace App\Model\User\Entities\Role;

use App\Model\Common\Contracts\Arrayable;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\ACL\Contracts\Role as RoleContract;

// Values
use App\Model\User\Entities\Role\Values\Name;

/**
 * @ORM\Entity(
 *     repositoryClass="App\Model\User\Repositories\RoleRepository"
 * )
 * @ORM\Table(
 *     uniqueConstraints={@ORM\UniqueConstraint(name="name_idx", columns={"name"})}
 * )
 */
class Role implements RoleContract, Arrayable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected int $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected string $name;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public static function create(Name $name): self
    {
        $model = new self;
        $model->setName($name->getValue());
        return $model;
    }

    public function hasPermissionTo($permission)
    {
        // TODO: Implement hasPermissionTo() method.
    }

    public function getPermissions()
    {
        // TODO: Implement getPermissions() method.
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name
        ];
    }
}
