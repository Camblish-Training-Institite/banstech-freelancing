<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Jobs\ContractController;

use App\Http\Controllers\Client\ContestController;

use App\Http\Controllers\Jobs\JobsController;

use App\Http\Controllers\ProfileController;


Route::prefix('client')->name('client.')->group(function () {
    //main nav links

    Route::get('/my-jobs', [ContractController::class, 'index'])->name('jobs.list');

    Route::get('/my-jobs', [JobsController::class, 'index_client'])->name('jobs.list');


    Route::get('/proposals', function() {
        return view('Users.clients.layouts.proposal-section');
    })->name('proposals.list');


    // Route::get('/contests', function() {
    //     return view('Users.clients.layouts.contest-section');
    // })->name('client.contests.list');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('jobs', JobsController::class);
});

Route::prefix('client/contests')->name('client.contests.')->group(function () {
    Route::get('/', [ContestController::class, 'index'])->name('index');
    Route::get('/create', [ContestController::class, 'create'])->name('create');
    Route::post('/store', [ContestController::class, 'store'])->name('store');
    Route::get('/{contest}/edit', [ContestController::class, 'edit'])->name('edit');
    Route::patch('/{contest}', [ContestController::class, 'update'])->name('update');
    Route::delete('/{contest}', [ContestController::class, 'destroy'])->name('destroy');
    Route::get('/{contest}/show', [ContestController::class, 'show'])->name('show');
})->middleware('auth');
