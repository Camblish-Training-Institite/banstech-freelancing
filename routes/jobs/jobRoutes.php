<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Jobs\JobsController;



Route::get('job/create', [JobsController::class, 'create'])
->name('job.create');
Route::post('job/store', [JobsController::class, 'store'])
->name('job.store');


