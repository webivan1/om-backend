<?php

namespace App\Providers;

use App\Model\Event\Entities\Event\Event;
use App\Model\Event\Repositories\EventRepository;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use LaravelDoctrine\ORM\Facades\EntityManager;

class RouteBindingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Route::bind('eventDetail', function ($value) {
            try {
                /** @var EventRepository $repo */
                $repo = EntityManager::getRepository(Event::class);
                return $repo->getDetail((int) $value);
            } catch (\DomainException $e) {
                abort(404, $e->getMessage());
            }
        });
    }
}
