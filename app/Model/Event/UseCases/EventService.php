<?php

namespace App\Model\Event\UseCases;

use App\Model\Event\Entities\Event\Event;
use App\Model\User\Entities\User\User;
use Doctrine\ORM\EntityManagerInterface;

class EventService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function canEditable(Event $event): bool
    {
        if ($event->isDraft() || $event->isRejected()) {
            return true;
        } elseif ($event->isModeration()) {
            return false;
        } elseif ($event->isApproved()) {
            //'now', new \DateTimeZone($event->getRegion()->getTimezone())
            $currentDate = new \DateTime();
            $meetingStart = $event->getStartAt()->modify('- 3 days');
            return $currentDate < $meetingStart;
        }

        throw new \DomainException('Unknown status');
    }

    public function canDelete(Event $event): bool
    {
        return $this->canEditable($event);
    }

    public function isAccessEvent(User $user, Event $event): bool
    {
        if ($user->hasRoleByName(['admin', 'moderator'])) {
            return true;
        }

        return $user->getId() === $event->getUser()->getId();
    }

    public function delete(Event $event): void
    {
        $this->canDelete($event);
        $this->deleteWithoutRules($event);
    }

    public function deleteWithoutRules(Event $event): void
    {
        $this->em->remove($event);
        $this->em->flush();

        // @todo add event delete
    }
}
