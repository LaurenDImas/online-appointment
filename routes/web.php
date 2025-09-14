<?php

use App\Http\Controllers\Public\HostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/calendar/apps/{user:uuid}', [HostController::class, 'getHostCalender'])->name('icalendar');

