<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Jobs\ContractController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Client\ContestController;
use App\Http\Controllers\Freelancer\FreelancerContestController;
use App\Http\Controllers\Jobs\JobsController;
use App\Http\Controllers\Jobs\ProposalController;


Route::prefix('freelancer')->name('freelancer.')->group(function () {
    //main nav links
    Route::get('/my-projects', [ContractController::class, 'index'])->name('jobs.list');

    Route::resource('/proposals', ProposalController::class);

    Route::get('/my-proposal/{id}', [ProposalController::class, 'myProposal'])->name('proposal.show');
    Route::get('/create_proposal/{job_id}', [ProposalController::class, 'create'])->name('proposal.create');

    // Route::get('/contests', function() {
    //     return view('Users.Freelancers.layouts.contest-section');
    // })->name('contests.list');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/services',function(){
    return view('Users.Freelancers.pages.services');
    })->name('services');

    //Message or Inbox
    Route::get('/inbox', function(){
        return view('Users.Freelancers.pages.inbox');
    })->name('inbox');
    //Earnings
    Route::get('/earnings',function(){
        return view('Users.Freelancers.pages.earnings');
    })->name('earnings');

    Route::get('/jobs/{id}', [JobsController::class, 'show_freelancer'])->name('jobs.show');

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


    Route::get('/services',function(){
    return view('dashboards.freelancer.services');
    })->name('services');

    //Message or Inbox
    Route::get('/inbox', function(){
        return view('dashboards.freelancer.inbox');
    })->name('inbox');
 })->middleware('auth');

   //Earnings
   Route::get('/earnings',function(){
    return view('dashboards.freelancer.earnings');
})->name('freelancer.earnings');