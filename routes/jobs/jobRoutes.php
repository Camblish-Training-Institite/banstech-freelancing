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

Route::get('geolocations', function () {
    return view('geo_location.testing');
})->name('geolocations');

Route::prefix('freelancer')->group(function () {
    Route::get('jobs-listing', [JobsController::class, 'index'])->name('jobs.listing');

})->middleware('auth');

