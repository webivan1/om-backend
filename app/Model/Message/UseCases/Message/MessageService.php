<?php

namespace App\Model\Message\UseCases\Message;

use App\Events\Message\NewMessageBroadcast;
use App\Model\Donation\Entities\Donation\Donation;
use App\Model\Event\Entities\Event\Event;
use App\Model\Event\UseCases\EventService;
use App\Model\Message\Entities\Message\Message;
use App\Model\Message\Entities\Message\Values\MessageText;
use App\Model\User\Entities\User\User;
use Doctrine\ORM\EntityManagerInterface;

class MessageService
{
    private EventService $eventService;
    private EntityManagerInterface $em;

    public function __construct(EventService $eventService, EntityManagerInterface $em)
    {
        $this->eventService = $eventService;
        $this->em = $em;
    }

    public function createFromEvent(User $user, Event $event, string $text): Message
    {
        if (!($event->isApproved() && ($event->isStartedEvent() || $event->isFinishedEvent()))) {
            throw new \DomainException('Access denied');
        }

        if (!$this->eventService->isAccessEvent($user, $event)) {
            throw new \DomainException('Access denied');
        }

        return $this->create($event, $text, $user);
    }

    public function create(Event $event, string $text, ?User $user = null, ?Donation $donation = null): Message
    {
        $organizer = false;

        if ($user) {
            $organizer = $this->eventService->isAccessEvent($user, $event);
        }

        $text = new MessageText($text);

        $message = Message::create($event, $text, $organizer, $user, $donation);

        broadcast(new NewMessageBroadcast($message));

        $this->em->persist($message);
        $this->em->flush();

        return $message;
    }
}
