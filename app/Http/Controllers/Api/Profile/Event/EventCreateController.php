<?php

namespace App\Http\Controllers\Api\Profile\Event;

use App\Http\Requests\Api\Profile\Event\EventCreateRequest;
use App\Model\Event\UseCases\EventCreate\EventCreateDto;
use App\Model\Event\UseCases\EventCreate\EventCreateService;

class EventCreateController
{
    public function index(EventCreateRequest $request, EventCreateService $service)
    {
        $dto = new EventCreateDto();
        $dto->title = $request->input('title');
        $dto->description = $request->input('description');
        $dto->startAt = $request->input('startAt');
        $dto->duration = $request->input('duration');
        $dto->region = $request->input('region');

        try {
            $event = $service->create($request->user(), $dto);
            return [
                'status' => 'success',
                'event' => $event->toArray()
            ];
        } catch (\DomainException $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}
