<?php

namespace App\Http\Controllers\Api\Event;

use App\Http\Requests\Api\Event\EventListRequest;
use App\Model\Event\Entities\Event\Event;
use App\Model\Event\Repositories\DonationRepository;
use LaravelDoctrine\ORM\Facades\EntityManager;

class EventListController
{
    public function index(EventListRequest $request)
    {
        /** @var DonationRepository $repo */
        $repo = EntityManager::getRepository(Event::class);

        $perPage = 9;

        $dataProvider = $repo->publicList($request->all(['region']), $perPage);

        return [
            'total' => $dataProvider->total(),
            'items' => array_map(
                function (array $data) {
                    return array_merge($data[0]->toArray(), array_slice($data, 1));
                },
                $dataProvider->items()
            ),
            'currentPage' => $dataProvider->currentPage(),
            'perPage' => $perPage
        ];
    }
}
