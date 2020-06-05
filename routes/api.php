<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth.token']
], function () {
    Route::get('user', fn(Request $request) => $request->user()->toArray());

    Route::group([
       'middleware' => ['has.roles:admin|moderator|organizer']
    ], function () {
        // Profile events
        Route::post('/profile/event/list', 'Profile\Event\EventListController@index');
        Route::post('/profile/event/create', 'Profile\Event\EventCreateController@index');
        Route::get('/profile/event/edit/{event}', 'Profile\Event\EventUpdateController@detail')
            ->where('eventDetail', '\d+');
        Route::post('/profile/event/edit/{event}', 'Profile\Event\EventUpdateController@update')
            ->where('eventDetail', '\d+');
    });
});

// Auth
Route::post('login', 'Auth\LoginController@login');

Route::get('region/list', 'RegionController@list');
Route::post('event/list', 'Event\EventListController@index');
Route::get('/event/view/{eventDetail}', 'Event\EventDetailController@index')
    ->where('eventDetail', '\d+');
Route::get('/event/view/{eventDetail}/stat', 'Event\EventDetailController@statistic')
    ->where('eventDetail', '\d+');

Route::post('/donation/create', 'Donation\DonationCreateController@create');
Route::post('/donation/:donation/payment', 'Donation\DonationCreateController@create')
    ->name('donation.payment')
    ->where('donation', '\d+');

// Check transaction
Route::any('/donation/:source/check', 'Donation\DonationPayPalHandlerController@check')
    ->name('donation.handler');
Route::any('/donation/:source/failed', 'Donation\DonationHandlerController@failed')
    ->name('donation.handler.failed');
