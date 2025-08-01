<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectManager\ProjectManagerDashboardController;
use App\Http\Controllers\Admin\ManagementRequestController;
use App\Http\Controllers\ProjectManager\ProfileController;

// routes/admin.php
Route::group(['prefix' => 'project-manager'], function () {
    Route::get('/dashboard', [ProjectManagerDashboardController::class, 'index'])->middleware('auth')->name('pm.dashboard');

    Route::get('/inbox', function(){
    return view('project-manager.pages.inbox');
    })->name('pm.inbox');

    Route::get('/projects', function(){
        return view('project-manager.pages.projects');
    })->name('pm.projects');

    Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('pm.profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('pm.profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('pm.profile.destroy');
});
});

Route::post('/management-requests/{request}/accept', [ManagementRequestController::class, 'accept'])
    ->name('admin.management-requests.accept');

Route::post('/management-requests/{request}/reject', [ManagementRequestController::class, 'reject'])
    ->name('admin.management-requests.reject');


//project manager pages
