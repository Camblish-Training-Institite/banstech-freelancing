<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Jobs\ContractController;
use App\Http\Controllers\ProfileController;


Route::prefix('client')->name('client.')->group(function () {
    //main nav links
    Route::get('/my-jobs', [ContractController::class, 'index'])->name('jobs.list');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/proposals', function() {
        return view('Users.clients.layouts.proposal-section');
    })->name('client.proposals.list');

    Route::get('/contests', function() {
        return view('Users.clients.layouts.contest-section');
    })->name('client.contests.list');
});
