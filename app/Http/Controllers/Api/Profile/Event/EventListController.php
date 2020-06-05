<?php

namespace App\Http\Controllers\Api\Profile\Event;

use App\Http\Requests\Api\Profile\Event\EventListFilterRequest;
use App\Model\Event\Entities\Event\Event;
use App\Model\Event\Repositories\EventRepository;
use LaravelDoctrine\ORM\Facades\EntityManager;

class EventListController
{
    public function index(EventListFilterRequest $request)
    {
        /** @var EventRepository $repo */
        $repo = EntityManager::getRepository(Event::class);

        $perPage = 10;

        $dataProvider = $repo->listByUser($request->user(), $perPage, $request->all());

        return [
            'total' => $dataProvider->total(),
            'items' => array_map(fn(Event $event) => $event->toArray(), $dataProvider->items()),
            'currentPage' => $dataProvider->currentPage(),
            'perPage' => $perPage
        ];
    }
}
