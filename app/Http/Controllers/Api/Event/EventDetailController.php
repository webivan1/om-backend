<?php

namespace App\Http\Controllers\Api\Event;

use App\Model\Event\Entities\Event\Event;
use App\Model\Event\Entities\EventStatistic\EventStatistic;
use App\Model\Event\Repositories\EventStatisticRepository;
use LaravelDoctrine\ORM\Facades\EntityManager;

class EventDetailController
{
    public function index(Event $eventDetail)
    {
        return [
            'status' => 'success',
            'detail' => $eventDetail->toArray()
        ];
    }

    public function statistic(Event $eventDetail)
    {
        /** @var EventStatisticRepository $repo */
        $repo = EntityManager::getRepository(EventStatistic::class);

        return response()->json(
            array_map(
                fn(EventStatistic $eventStatistic) => $eventStatistic->toArray(),
                $repo->findList($eventDetail->getId())
            )
        );
    }
}
