<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Freelancer\JobController;
use App\Http\Controllers\Freelancer\ProposalController;
use App\Http\Controllers\Freelancer\ContestController;
use App\Http\Controllers\Freelancer\CompletedProjectsController;
use App\Http\Controllers\Freelancer\NextOpportunitiesController;
use App\Http\Controllers\Freelancer\NewOffersController;
use App\Http\Controllers\Freelancer\ViewOffersController;
use App\Http\Controllers\Freelancer\EarningsController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

Route::get('/freelancer/active-jobs', [JobController::class, 'activeJobs'])
        ->name('freelancer.active-jobs');

        Route::get('freelancer/proposals', [ProposalController::class, 'proposals'])
        ->name('freelancer.proposals');

        Route::get('freelancer/contests', [ContestController::class, 'contests'])
        ->name('freelancer.contests');

        Route::get('freelancer/completed-projects', [CompletedProjectsController::class, 'completedProjects'])
        ->name('freelancer.completed-projects');

        Route::get('Components/find-next-opportunity', [NextOpportunitiesController::class, 'nextOpportunities'])
        ->name('Components.find-next-opportunity');

        Route::get('Components/new-offer-card', [NewOffersController::class, 'newOffers'])
        ->name('Components.new-offer-card');

        Route::get('Components/new-offer-card', [NewOffersController::class, 'newOffers'])
        ->name('Components.new-offer-card');

        Route::get('Components/view-offer', [ViewOffersController::class, 'viewOffers'])
        ->name('Components.view-offer');

        Route::get('freelancer/earnings', [EarningsController::class, 'myEarnings'])
        ->name('freelancer.earnings');
});

require __DIR__.'/auth.php';
require __DIR__.'/jobs/jobRoutes.php';
