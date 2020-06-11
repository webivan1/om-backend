<?php

namespace App\Http\Controllers\Api\Chat;

use App\Http\Requests\Api\Chat\CreateMessageRequest;
use App\Model\Event\Entities\Event\Event;
use App\Model\Message\Entities\Message\Message;
use App\Model\Message\Repositories\MessageRepository;
use App\Model\Message\UseCases\Message\MessageService;
use LaravelDoctrine\ORM\Facades\EntityManager;

class MessagesController
{
    public function list(Event $eventDetail)
    {
        if (!$eventDetail->isApproved()) {
            abort(403, 'Access denied');
        }

        /** @var MessageRepository $repo */
        $repo = EntityManager::getRepository(Message::class);

        $dataProvider = $repo->listForChat($eventDetail->getId(), $perPage = 40);

        return [
            'total' => $dataProvider->total(),
            'items' => array_reverse(array_map(
                fn(Message $message) => $message->toArray(),
                $dataProvider->items()
            )),
            'currentPage' => $dataProvider->currentPage(),
            'perPage' => $perPage
        ];
    }

    public function create(Event $eventDetail, CreateMessageRequest $request, MessageService $service)
    {
        try {
            $message = $service->createFromEvent(
                $request->user(),
                $eventDetail,
                $request->input('message')
            );

            return ['status' => 'success', 'message' => $message->toArray()];
        } catch (\DomainException $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
