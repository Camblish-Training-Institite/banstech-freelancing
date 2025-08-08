<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Jobs\ContractController;


Route::prefix('client')->group(function () {
    //main nav links
    Route::get('/my-jobs', [ContractController::class, 'index'])->name('client.jobs.list');

    Route::get('/proposals', function() {
        return view('Users.clients.layouts.proposal-section');
    })->name('client.proposals.list');

    Route::get('/contests', function() {
        return view('Users.clients.layouts.contest-section');
    })->name('client.contests.list');
});
