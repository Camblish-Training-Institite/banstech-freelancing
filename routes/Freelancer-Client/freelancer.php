<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Jobs\ContractController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Client\ContestController;
use App\Http\Controllers\Freelancer\FreelancerContestController;
use App\Http\Controllers\Freelancer\MilestoneController;
use App\Http\Controllers\Jobs\JobsController;
use App\Http\Controllers\Jobs\ProposalController;
use App\Http\Controllers\Payments\PayoutController; 
use App\Http\Controllers\SettingsController;

Route::prefix('freelancer')->name('freelancer.')->group(function () {
    //main nav links

    //freelancer job routes
    Route::get('/job/{id}/show', [JobsController::class, 'show_freelancer'])->name('jobs.show');

    //freelancer project routes
    Route::get('/my-projects', [ContractController::class, 'index'])->name('projects.list');
    Route::resource('projects', ContractController::class);

    //freelancer project milestone routes
    Route::get('/projects/{project}/milestone', [ContractController::class, 'requestMilestone'])->name('projects.milestone.request');
    Route::get('/project/milestone/{milestone}', [MilestoneController::class, 'show'])->name('milestone.show');

    //freelancer proposal routes
    Route::resource('/proposals', ProposalController::class);
    Route::get('/my-proposal/{id}', [ProposalController::class, 'myProposal'])->name('proposal.show');
    Route::get('/create_proposal/{job_id}', [ProposalController::class, 'create'])->name('proposal.create');

    //freelancer profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.update.avatar');
    Route::patch('/profile/AboutMe', [ProfileController::class, 'updateAboutMe'])->name('profile.updateAboutMe');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile', [ProfileController::class, 'updateProfile'])->name('profile.updateProfile');
    Route::patch('/profile/address', [ProfileController::class, 'updateAddress'])->name('profile.updateAddress');
    // Route::patch('/profile/skills', [ProfileController::class, 'updateSkills'])->name('profile.updateSkills');
    Route::patch('/profile/qualifications', [ProfileController::class, 'updateQualifications'])->name('profile.updateQualifications');
    Route::patch('/profile/certificates', [ProfileController::class, 'updateCertifications'])->name('profile.updateCertificates');
    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');
  


    //services
    Route::get('/services',function(){
    return view('Users.Freelancers.pages.services');
    })->name('services');

    //Message or Inbox
    Route::get('/inbox', function(){
        return view('Users.Freelancers.pages.inbox');
    })->name('inbox');
    
});

//freelancer  contests routes
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

//     Route::get('/earnings',function(){
//     return view('dashboards.freelancer.earnings');
// })->name('freelancer.earnings');

Route::get('/earnings',[PayoutController::class, 'index'])
->name('freelancer.earnings')->middleware('auth');

//Myprofile
Route::get('/freelancer/myprofile',function(){
    return view('Users.Freelancers.layouts.user-profile');
})->name('freelancer.myprofile')->middleware('auth');

//update profile
// Route::post('/freelancer/updateProfile', [ProfileController::class, 'update'])->name('freelancer.updateProfile');
// Route::put('/freelancer/profile', [ProfileController::class, 'updateProfile'])->name('freelancer.profile.updateProfile');
// Route::patch('/freelancer/profile', [ProfileController::class, 'updateAboutMe'])->name('freelancer.profile.updateAboutMe');
