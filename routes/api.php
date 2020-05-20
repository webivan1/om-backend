<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth.token']
], function () {
    Route::get('user', fn(Request $request) => $request->user()->toArray());

    // Profile events
    Route::post('/profile/event/list', 'Profile\Event\EventListController@index');
});

// Auth
Route::post('login', 'Auth\LoginController@login');

Route::get('region/list', 'RegionController@list');
Route::post('event/list', 'Event\EventListController@index');
Route::get('/event/view/{eventDetail}', 'Event\EventDetailController@index')->where('eventDetail', '\d+');
Route::get('/event/view/{eventDetail}/stat', 'Event\EventDetailController@statistic')->where('eventDetail', '\d+');
