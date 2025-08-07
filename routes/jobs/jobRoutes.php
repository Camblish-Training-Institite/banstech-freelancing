<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Jobs\JobsController;


Route::resource('jobs', JobsController::class);

// Route::get('job/create', [JobsController::class, 'create'])
// ->name('job.create');

// Route::post('job/store', [JobsController::class, 'store'])
// ->name('job.store');

// Route::get('job/{id}/edit', [JobsController::class, 'edit'])
// ->name('job.edit');

// Route::put('job/{id}/update', [JobsController::class, 'update'])
// ->name('job.update');

// Route::get('job/{id}/delete', [JobsController::class, 'delete'])
// ->name('job.delete');
