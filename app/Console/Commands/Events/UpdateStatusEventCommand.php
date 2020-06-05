<?php

namespace App\Console\Commands\Events;

use App\Model\Event\Entities\Event\Event;
use App\Model\Event\Repositories\EventRepository;
use App\Model\Event\UseCases\EventUpdate\EventUpdateService;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Console\Command;

class UpdateStatusEventCommand extends Command
{
    protected $signature = 'update:event:status {event} {status} {message?}';
    protected $description = 'Command description';

    public function handle(EntityManagerInterface $em, EventUpdateService $service)
    {
        /** @var EventRepository|\Doctrine\Persistence\ObjectRepository $repo */

        $repo = $em->getRepository(Event::class);

        /** @var Event $event */
        if (!$event = $repo->find((int)$this->argument('event'))) {
            $this->error('This event is not found');
        } else {
            $status = $this->argument('status');

            switch ($status) {
                case Event::STATUS_REJECT:
                    $service->toReject($event, $this->argument('message'));
                break;
                case Event::STATUS_APPROVED:
                    $service->toApprove($event);
                break;
            }
        }
    }
}
