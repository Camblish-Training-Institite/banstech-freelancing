<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Jobs\ContractController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Client\ContestController;
use App\Http\Controllers\Freelancer\FreelancerContestController;


Route::prefix('freelancer')->name('freelancer.')->group(function () {
    //main nav links
    Route::get('/my-jobs', [ContractController::class, 'index'])->name('jobs.list');

    Route::get('/proposals', function() {
        return view('Users.Freelancers.layouts.proposal-section');
    })->name('proposals.list');

    // Route::get('/contests', function() {
    //     return view('Users.Freelancers.layouts.contest-section');
    // })->name('contests.list');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

Route::prefix('freelancer/contests')->name('freelancer.contests.')->group(function () {
    Route::get('/', [FreelancerContestController::class, 'index'])->name('index');
    Route::get('/{contest}', [FreelancerContestController::class, 'show'])->name('show');

    // ADD THIS: Route to display the submission form
    Route::get('/{contest}/submit', [FreelancerContestController::class, 'showSubmitForm'])->name('submit.show');

    // UPDATE THIS: Route to handle the form submission
    Route::post('/{contest}/submit', [FreelancerContestController::class, 'storeEntry'])->name('submit.store');

    // Your existing withdraw route (if you have one)
    Route::delete('/entry/{entry}/withdraw', [FreelancerContestController::class, 'withdraw'])->name('entry.withdraw');
})->middleware('auth');



