<?php

namespace App\Http\Controllers\Api\Profile\Event;

use App\Model\Event\Entities\Event\Event;
use App\Model\Event\Repositories\EventRepository;
use Illuminate\Http\Request;
use LaravelDoctrine\ORM\Facades\EntityManager;

class EventListController
{
    public function index(Request $request)
    {
        /** @var EventRepository $repo */
        $repo = EntityManager::getRepository(Event::class);

        $perPage = 10;

        $dataProvider = $repo->listByUser($request->user(), $perPage);

        return [
            'total' => $dataProvider->total(),
            'items' => array_map(fn(Event $event) => $event->toArray(), $dataProvider->items()),
            'currentPage' => $dataProvider->currentPage(),
            'perPage' => $perPage
        ];
    }
}
