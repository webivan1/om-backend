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
            ->where('event', '\d+');
        Route::post('/profile/event/edit/{event}', 'Profile\Event\EventUpdateController@update')
            ->where('event', '\d+');
        Route::delete('/profile/event/remove/{event}', 'Profile\Event\EventRemoveController@index')
            ->where('event', '\d+');

        Route::put('/chat/message/{eventDetail}/create', 'Chat\MessagesController@create')
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

Route::get('/chat/messages/{eventDetail}', 'Chat\MessagesController@list')
    ->where('eventDetail', '\d+');

Route::post('/donation/create', 'Donation\DonationCreateController@create');
Route::get('/donation/{donation}/payment', 'Donation\DonationPaymentController@url')
    ->name('donation.payment')
    ->where('donation', '\d+');

// Check transaction
Route::any('/donation/{donation}/check', 'Donation\DonationHandlerController@check')
    ->name('donation.handler');
Route::any('/donation/{donation}/failed', 'Donation\DonationHandlerController@failed')
    ->name('donation.handler.failed');
