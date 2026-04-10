<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Jobs\JobMapController;
use App\Http\Controllers\Client\ClientMapController;

// The {job} part is a placeholder for the Job ID
Route::get('/job/{job}/map', [JobMapController::class, 'show'])->name('freelancer.job.map');

Route::get('/client/job/{job}/map', [ClientMapController::class, 'showMap'])->name('client.job.map');