<?php

namespace App\Model\Event\UseCases\EventUpdate;

use App\Model\Event\Entities\Event\Event;
use App\Model\Event\Entities\Event\Values\Description;
use App\Model\Event\Entities\Event\Values\EventDate;
use App\Model\Event\Entities\Event\Values\Title;
use App\Model\Event\UseCases\EventService;
use Doctrine\ORM\EntityManagerInterface;

class EventUpdateService
{
    public EventService $service;
    private EntityManagerInterface $em;

    public function __construct(EventService $service, EntityManagerInterface $em)
    {
        $this->service = $service;
        $this->em = $em;
    }

    public function update(Event $event, EventUpdateDto $dto): Event
    {
        $this->service->canEditable($event);
        return $this->updateWithoutRules($event, $dto);
    }

    public function updateWithoutRules(Event $event, EventUpdateDto $dto): Event
    {
        $hours = ceil($dto->duration / 3600);

        $title = new Title($dto->title);
        $description = new Description($dto->description);
        $date = new EventDate(
            $event->getStartAt()->format('Y-m-d H:i:s'),
            $event->getRegion()->getTimezone(),
            "+ $hours hour"
        );

        $event->update($title, $description, $date);

        $this->em->persist($event);
        $this->em->flush();

        // @todo event update - send message to admin

        return $event;
    }

    public function toApprove(Event $event): Event
    {
        $event->toApprove();
        $this->em->persist($event);
        $this->em->flush();

        // @todo send one user as author event

        return $event;
    }

    public function toReject(Event $event, string $reason): Event
    {
        $event->toReject();
        $this->em->persist($event);
        $this->em->flush();

        // @todo send one user as author event with reject reason

        return $event;
    }
}
