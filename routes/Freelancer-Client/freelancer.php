<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Jobs\ContractController;
use App\Http\Controllers\ProfileController;


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

});
//This is for the side bar navigations
//side-bar services
Route::get('/freelancer/services',function(){
return view('dashboards.freelancer.services');
})->name('services');

//This is for -Browse the available jobs-
Route::get('/freelancer/browseJobs', function(){
 return view('dashboards.freelancer.browseJobs');
})->name('browseJobs');
//Message or Inbox
Route::get('/freelancer/inbox', function(){
    return view('dashboards.freelancer.inbox');
})->name('inbox');
//Earnings
Route::get('/freelancer/earnings',function(){
    return view('dashboards.freelancer.earnings');
})->name('earnings');