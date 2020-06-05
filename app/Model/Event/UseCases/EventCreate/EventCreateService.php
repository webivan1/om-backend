<?php

namespace App\Model\Event\UseCases\EventCreate;

use App\Model\Event\Entities\Event\Event;
use App\Model\Event\Entities\Event\Values\Description;
use App\Model\Event\Entities\Event\Values\EventDate;
use App\Model\Event\Entities\Event\Values\Title;
use App\Model\Region\Entities\Region\Region;
use App\Model\Region\Repositories\RegionRepository;
use App\Model\User\Entities\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class EventCreateService
{
    private EntityManagerInterface $em;

    /** @var RegionRepository|ObjectRepository */
    private ObjectRepository $repoRegion;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repoRegion = $this->em->getRepository(Region::class);
    }

    public function create(User $user, EventCreateDto $dto): Event
    {
        $hours = ceil($dto->duration / 3600);

        $region = $this->repoRegion->getOne($dto->region);

        $title = new Title($dto->title);
        $description = new Description($dto->description);

        $date = new EventDate($dto->startAt, $region->getTimezone(), "+ $hours hour");

        [$startAt] = $date->getValue();

        $minDate = (new \DateTime())->modify('+ 7 days')
            ->setTime(0, 0, 0);

        if ($minDate > $startAt) {
            throw new \DomainException('Новый митинг должен быть создан не ранее чем ' . $minDate->format('d.m.Y'));
        }

        $model = Event::create(
            $user,
            $region,
            $title,
            $description,
            $date
        );

        $this->em->persist($model);
        $this->em->flush();

        // @todo New event has been created

        return $model;
    }
}
