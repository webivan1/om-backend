<?php

namespace App\Providers;

use App\Model\Donation\Entities\Donation\Donation;
use App\Model\Donation\Repositories\DonationRepository;
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

        Route::bind('donation', function ($value) {
            try {
                /** @var DonationRepository $repo */
                $repo = EntityManager::getRepository(Donation::class);
                return $repo->getOne((int) $value);
            } catch (\DomainException $e) {
                abort(404, $e->getMessage());
            }
        });
    }
}
