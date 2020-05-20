<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => [
        'guest'
    ]
], function () {
    Route::get('/register', 'Auth\RegisterController@form')->name('admin.register');
    Route::post('/register', 'Auth\RegisterController@handle')->name('admin.register');

    Route::get('/', 'Auth\LoginController@form')->name('admin.login');
    Route::post('/', 'Auth\LoginController@handle')->name('admin.login');
});

Route::group([
    'middleware' => [
        'auth'
    ],
], function () {

});


