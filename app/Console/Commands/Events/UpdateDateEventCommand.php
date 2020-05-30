<?php

namespace App\Console\Commands\Events;

use App\Model\Event\Entities\Event\Event;
use App\Model\Event\Repositories\DonationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Console\Command;

class UpdateDateEventCommand extends Command
{
    protected $signature = 'update:event:date {event} {modify}';
    protected $description = 'Command description';

    public function handle(EntityManagerInterface $em)
    {
        /** @var DonationRepository|\Doctrine\Persistence\ObjectRepository $repo */

        $repo = $em->getRepository(Event::class);

        /** @var Event $event */
        if (!$event = $repo->find((int)$this->argument('event'))) {
            $this->error('This event is not found');
        } else {
            $date = new \DateTime();
            $date->modify($this->argument('modify'));
            $event->setFinishAt($date);

            $em->persist($event);
            $em->flush();
        }
    }
}
