<?php

namespace App\Model\Event\UseCases\EventStatistic;

use App\Model\Event\Entities\Event\Event;
use App\Model\Event\Entities\EventStatistic\EventStatistic;
use App\Model\Event\Repositories\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class CreateEventService
{
    private EntityManagerInterface $em;

    /** @var EventRepository|ObjectRepository */
    private ObjectRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(Event::class);
    }

    public function create(int $eventId, int $total): EventStatistic
    {
        /** @var Event|null $event */
        if (!$event = $this->repo->find($eventId)) {
            throw new \DomainException('This event is not exists');
        }

        $stat = EventStatistic::create($event, $total);

        $this->em->persist($stat);
        $this->em->flush();

        return $stat;
    }
}
