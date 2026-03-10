<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::group(['prefix' => 'chat', 'as' => 'chat.'], function () {
        Route::view('recipient/{id}', 'chat')->name('recipient');
    });
});

require __DIR__.'/settings.php';
