<?php

namespace App\Http\Controllers\Api\Profile\Event;

use App\Http\Requests\Api\Profile\Event\EventUpdateRequest;
use App\Model\Event\Entities\Event\Event;
use App\Model\Event\UseCases\EventUpdate\EventUpdateDto;
use App\Model\Event\UseCases\EventUpdate\EventUpdateService;
use App\Model\User\Entities\User\User;
use Illuminate\Http\Request;

class EventUpdateController
{
    private EventUpdateService $service;

    public function __construct(EventUpdateService $service)
    {
        $this->service = $service;
    }

    private function checkAccess(User $user, Event $event): void
    {
        if ($this->service->service->canEditable($event) && $this->service->service->isAccessEvent($user, $event)) {
            return;
        }

        abort(403, 'Access denied');
    }

    public function detail(Request $request, Event $event)
    {
        $this->checkAccess($request->user(), $event);

        return $event->toArray();
    }

    public function update(EventUpdateRequest $request, Event $event)
    {
        $this->checkAccess($request->user(), $event);

        $dto = new EventUpdateDto();
        $dto->title = $request->input('title');
        $dto->description = $request->input('description');
        $dto->duration = $request->input('duration');

        try {
            $event = $this->service->update($event, $dto);
            return ['status' => 'success', 'event' => $event->toArray()];
        } catch (\DomainException $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
