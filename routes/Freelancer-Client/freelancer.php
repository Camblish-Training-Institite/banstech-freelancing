<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Jobs\ContractController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Client\ContestController;
use App\Http\Controllers\Freelancer\FreelancerContestController;
use App\Http\Controllers\Jobs\JobsController;
use App\Http\Controllers\Jobs\ProposalController;
use App\Http\Controllers\Payments\PayoutController;

Route::prefix('freelancer')->name('freelancer.')->group(function () {
    //main nav links

    //freelancer job routes
    Route::get('/job/{id}/show', [JobsController::class, 'show_freelancer'])->name('jobs.show');

    //freelancer project routes
    Route::get('/my-projects', [ContractController::class, 'index'])->name('projects.list');
    Route::resource('projects', ContractController::class);

    //freelancer project milestone routes
    Route::get('/projects/{project}/milestone', [ContractController::class, 'requestMilestone'])->name('projects.milestone.request');

    //freelancer proposal routes
    Route::resource('/proposals', ProposalController::class);
    Route::get('/my-proposal/{id}', [ProposalController::class, 'myProposal'])->name('proposal.show');
    Route::get('/create_proposal/{job_id}', [ProposalController::class, 'create'])->name('proposal.create');

    //freelancer profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //services
    Route::get('/services',function(){
    return view('Users.Freelancers.pages.services');
    })->name('services');

    //Message or Inbox
    Route::get('/inbox', function(){
        return view('Users.Freelancers.pages.inbox');
    })->name('inbox');

    //Payouts Controllers for showing and updating payouts
    //Earnings
    Route::get('/earnings',[PayoutController::class, 'index'])->name('earnings');
    Route::patch('/earnings/{id}',[PayoutController::class,'update'])->name('earnings.update');
});

//freelancer contests routes
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
//    Route::get('/earnings',function(){
//     return view('dashboards.freelancer.earnings');
// })->name('freelancer.earnings');