<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Jobs\ContractController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Client\ContestController;
use App\Http\Controllers\Jobs\JobsController;
use App\Http\Controllers\Jobs\ProposalController;




Route::prefix('client')->name('client.')->group(function () {
    //main nav links

    // Route::get('/my-jobs', [ContractController::class, 'index'])->name('jobs.list');

    Route::get('/my-jobs', [JobsController::class, 'index_client'])->name('jobs.list');
  
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/proposals-list', [ProposalController::class, 'index_client'])->name('proposals.list');
    Route::resource('/proposals', ProposalController::class);
    Route::get('/proposals/{job}/show', [ProposalController::class, 'show'])->name('proposals.job.show');   

    // Route::get('/contests', function() {
    //     return view('Users.clients.layouts.contest-section');
    // })->name('client.contests.list');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/services',function(){
    return view('Users.Clients.pages.services');
    })->name('services');

    //Message or Inbox
    Route::get('/inbox', function(){
        return view('Users.Clients.pages.inbox');
    })->name('inbox');
    //Earnings
    Route::get('/earnings',function(){
        return view('Users.Clients.pages.earnings');
    })->name('earnings');

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

//This is for billing page situated under dashboards/clients...
Route::get('/billing',function(){
    return view('dashboards.client.billing');
})->name('billing');
