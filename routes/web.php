<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::group(['prefix' => 'message'], function () {
        Route::view('message/recipient/{id}', 'message')->name('message.recipient');
    });
});

require __DIR__.'/settings.php';
