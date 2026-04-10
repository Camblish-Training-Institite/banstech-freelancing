<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BanstechAdmin\LoginController;
use App\Http\Controllers\BanstechAdmin\JobController;
use App\Http\Controllers\BanstechAdmin\UserController; 
use App\Http\Controllers\BanstechAdmin\ContractController;

Route::get('/banstech-admin/dashboard', function() {
    return view('admin.dashboard');
})->name('admin.dashboard');

//Admin Authentication routes
Route::get('/banstech-admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/banstech-admin/login', [LoginController::class, 'store'])->name('admin.login.store');

Route::post('/banstech-admin/logout', [LoginController::class, 'destroy'])->name('admin.logout');

Route::group(['middleware' => ['auth:admin']], function () {
    // Admin protected routes go here
});


//Admin Job Routes
Route::resource('/banstech-admin/jobs', JobController::class, [
    'as' => 'admin'
]);

//Admin User Management Routes
Route::resource('/banstech-admin/users', UserController::class, [
    'as' => 'admin'
]);
Route::post('banstech-admin/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('admin.users.reset-password');


//Admin Project/Contract Management Routes
Route::resource('/banstech-admin/projects', ContractController::class, [
    'as' => 'admin'
]);


// Admin profile management routes
Route::get('/banstech-admin/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
Route::patch('/banstech-admin/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
Route::delete('/banstech-admin/profile', [ProfileController::class, 'destroy'])->name('admin.profile.destroy');