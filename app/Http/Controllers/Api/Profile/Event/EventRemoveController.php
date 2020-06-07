<?php

namespace App\Http\Controllers\Api\Profile\Event;

use App\Model\Event\Entities\Event\Event;
use App\Model\Event\UseCases\EventService;

class EventRemoveController
{
    public function index(Event $event, EventService $service)
    {
        try {
            $service->delete($event);
            return ['status' => 'success'];
        } catch (\DomainException $e) {
            return ['status' => 'error'];
        }
    }
}
