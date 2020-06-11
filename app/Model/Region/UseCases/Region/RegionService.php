<?php

namespace App\Model\Region\UseCases\Region;

use App\Model\Common\Contracts\SlugContract;
use App\Model\Region\Entities\Region\Region;
use App\Model\Region\Entities\Region\Values\Distance;
use App\Model\Region\Entities\Region\Values\Label;
use App\Model\Region\Entities\Region\Values\LatLng;
use App\Model\Region\Entities\Region\Values\Slug;
use App\Model\Region\Entities\Region\Values\Timezone;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class RegionService
{
    private EntityManagerInterface $em;
    private SlugContract $slug;
    private ObjectRepository $repository;

    public function __construct(EntityManagerInterface $em, SlugContract $slug)
    {
        $this->em = $em;
        $this->slug = $slug;
        $this->repository = $em->getRepository(Region::class);
    }

    public function create(RegionCreateDto $dto)
    {
        $slug = $dto->slug ?? $this->slug->transform($dto->label);

        if ($this->repository->findOneBy(compact('slug'))) {
            throw new \DomainException('This region is already exists');
        }

        $region = Region::create(
            new Label($dto->label),
            new Slug($slug),
            new LatLng($dto->lat, $dto->lng),
            new Distance($dto->distance),
            new Timezone($dto->timezone)
        );

        $this->em->persist($region);
        $this->em->flush();

        return $region;
    }

    public function update()
    {
        // @todo
    }

    public function delete()
    {
        // @todo
    }
}
