<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CalendarController;

Route::get('/google-calendar/callback', [CalendarController::class, 'index']);

Route::get('/', function () {
    return view('welcome');
});
