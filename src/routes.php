<?php

use Illuminate\Support\Facades\Route;

Route::prefix('accurate')->as('accurate.')->namespace("Buatin\Accurate\Controllers")->group(function () {
    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::get('connect', 'AuthController@connect')->name('connect');
    });
});
