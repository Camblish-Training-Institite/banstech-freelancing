<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectManager\ProjectManagerDashboardController;
use App\Http\Controllers\Admin\ManagementRequestController;
use App\Http\Controllers\ProjectManager\ProfileController;
use App\Http\Controllers\ProjectManager\ContractController;
use App\Http\Controllers\ProjectManager\MilestoneController;

// routes/admin.php
Route::prefix('project-manager')->name('pm.')->group(function () {

    //project manager project routes
    Route::get('/my-projects', [ContractController::class, 'index'])->name('projects.list');
    Route::get('/project/{id}/show', [ContractController::class, 'show'])->name('project.show');
    // Route::resource('projects', ContractController::class)->except(['index', 'show']);

    //project manager milestone routes
    Route::get('/project/{project}/milestones/create', [MilestoneController::class, 'create'])->name('project.milestones.create');
    Route::post('/project/{project}/milestones/store', [MilestoneController::class, 'store'])->name('project.milestone.store');
    Route::get('/project/{project}/milestones/edit/{milestone}', [MilestoneController::class, 'edit'])->name('project.milestone.edit');
    Route::post('/project/{project}/milestones/store/{milestone}', [MilestoneController::class, 'update'])->name('project.milestone.update');
    Route::get('/project/{project}/milestones/destroy/{milestone}', [MilestoneController::class, 'destroy'])->name('project.milestone.destroy');
    Route::get('/project/{project}/milestones/release/{milestone}', [MilestoneController::class, 'releasePayment'])->name('project.milestone.release');
  

    //project management dashboard routes
    Route::get('/dashboard', [ProjectManagerDashboardController::class, 'index'])->middleware('auth')->name('dashboard');
    Route::get('/requests', [ProjectManagerDashboardController::class, 'requests'])->name('requests');
    Route::get('/projects', [ProjectManagerDashboardController::class, 'projects'])->name('projects');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

})->middleware('auth');

Route::post('/management-requests/{request}/accept', [ManagementRequestController::class, 'accept'])
    ->name('admin.management-requests.accept');

Route::post('/management-requests/{request}/reject', [ManagementRequestController::class, 'reject'])
    ->name('admin.management-requests.reject');

//project manager pages
