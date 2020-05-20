<?php

namespace App\Model\Region\Entities\Region;

use App\Model\Common\Contracts\Arrayable;
use App\Model\Region\Entities\Region\Values\Distance;
use App\Model\Region\Entities\Region\Values\Label;
use App\Model\Region\Entities\Region\Values\LatLng;
use App\Model\Region\Entities\Region\Values\Slug;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(
 *     repositoryClass="App\Model\Region\Repositories\RegionRepository"
 * )
 * @ORM\Table(
 *     uniqueConstraints={@ORM\UniqueConstraint(name="slug_idx", columns={"slug"})}
 * )
 */
class Region implements Arrayable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $label;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private string $slug;

    /**
     * @ORM\Column(type="float")
     */
    private float $lat;

    /**
     * @ORM\Column(type="float")
     */
    private float $lng;

    /**
     * @ORM\Column(type="integer")
     */
    private int $distance;

    /**
     * @ORM\Column(type="string")
     */
    private string $timezone;

    public function getTimezone(): string
    {
        return $this->timezone;
    }

    public function setTimezone(string $timezone): void
    {
        $this->timezone = $timezone;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getLat(): float
    {
        return $this->lat;
    }

    public function setLat(float $lat): void
    {
        $this->lat = $lat;
    }

    public function getLng(): float
    {
        return $this->lng;
    }

    public function setLng(float $lng): void
    {
        $this->lng = $lng;
    }

    public function getDistance(): int
    {
        return $this->distance;
    }

    public function setDistance(int $distance): void
    {
        $this->distance = $distance;
    }

    public static function create(
        Label $label,
        Slug $slug,
        LatLng $latLng,
        Distance $distance
    ): self
    {
        $c = new \DateTime();
        $c->setTimezone(new \DateTimeZone());

        $model = new self;
        $model->setLabel($label->getValue());
        $model->setSlug($slug->getValue());
        $model->setDistance($distance->getValue());

        [$lat, $lng] = $latLng->getValue();

        $model->setLat($lat);
        $model->setLng($lng);

        return $model;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'label' => $this->label,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'distance' => $this->distance,
            'timezone' => $this->timezone
        ];
    }
}
