<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Jobs\JobMapController;

// The {job} part is a placeholder for the Job ID
Route::get('/job/{job}/map', [JobMapController::class, 'show'])->name('freelancer.job.map');