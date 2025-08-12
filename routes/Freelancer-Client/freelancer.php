<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Jobs\ContractController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Jobs\JobsController;


Route::prefix('freelancer')->name('freelancer.')->group(function () {
    //main nav links
    Route::get('/my-jobs', [ContractController::class, 'index'])->name('jobs.list');

    Route::get('/proposals', function() {
        return view('Users.Freelancers.layouts.proposal-section');
    })->name('proposals.list');

    Route::get('/contests', function() {
        return view('Users.Freelancers.layouts.contest-section');
    })->name('contests.list');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/jobs/{id}', [JobsController::class, 'show_freelancer'])->name('jobs.show');

});
