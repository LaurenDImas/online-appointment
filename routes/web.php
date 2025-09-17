<?php

use App\Http\Controllers\Public\HostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
//    $categories = Cache::remember('categories', 3600, function () {
//       return
//    });
    return view('welcome');
});


Route::get('/calendar/apps/{user:uuid}', [HostController::class, 'getHostCalender'])->name('icalendar');

Route::get('/api-documentation', function () {
    return view('api-docs');
});
