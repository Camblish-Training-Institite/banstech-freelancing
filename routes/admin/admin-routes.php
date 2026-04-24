<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BanstechAdmin\LoginController;
use App\Http\Controllers\BanstechAdmin\JobController;
use App\Http\Controllers\BanstechAdmin\UserController; 
use App\Http\Controllers\BanstechAdmin\ContractController;
use App\Http\Controllers\BanstechAdmin\MilestoneController;
use App\Http\Controllers\BanstechAdmin\WithdrawalRequestController;
use App\Http\Controllers\SettingsController;
use App\Models\WithdrawalRequest;

Route::get('/banstech-admin/dashboard', function() {
    $pendingWithdrawals = WithdrawalRequest::where('status', 'pending')->count();
    $confirmedWithdrawals = WithdrawalRequest::where('status', 'confirmed')->count();
    $processedWithdrawals = WithdrawalRequest::where('status', 'processed')->count();

    return view('admin.dashboard', compact('pendingWithdrawals', 'confirmedWithdrawals', 'processedWithdrawals'));
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

// Admin milestone management routes
Route::resource('/banstech-admin/milestones', MilestoneController::class, [
    'as' => 'admin'
])->except(['create', 'store']);
Route::get('/banstech-admin/projects/{project}/milestones/create', [MilestoneController::class, 'create'])
    ->name('admin.projects.milestones.create');
Route::post('/banstech-admin/projects/{project}/milestones', [MilestoneController::class, 'store'])
    ->name('admin.projects.milestones.store');
Route::patch('/banstech-admin/milestones/{milestone}/status', [MilestoneController::class, 'updateStatus'])
    ->name('admin.milestones.update-status');


// Admin profile management routes
Route::get('/banstech-admin/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
Route::patch('/banstech-admin/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
Route::delete('/banstech-admin/profile', [ProfileController::class, 'destroy'])->name('admin.profile.destroy');
Route::get('/banstech-admin/settings', [SettingsController::class, 'edit'])->name('admin.settings.edit');
Route::patch('/banstech-admin/settings', [SettingsController::class, 'update'])->name('admin.settings.update');

Route::middleware('auth:admin')->group(function () {
    Route::get('/banstech-admin/withdrawals', [WithdrawalRequestController::class, 'index'])->name('admin.withdrawals.index');
    Route::get('/banstech-admin/withdrawals/{withdrawal}', [WithdrawalRequestController::class, 'show'])->name('admin.withdrawals.show');
    Route::patch('/banstech-admin/withdrawals/{withdrawal}/confirm', [WithdrawalRequestController::class, 'confirm'])->name('admin.withdrawals.confirm');
    Route::patch('/banstech-admin/withdrawals/{withdrawal}/process', [WithdrawalRequestController::class, 'process'])->name('admin.withdrawals.process');
    Route::patch('/banstech-admin/withdrawals/{withdrawal}/fail', [WithdrawalRequestController::class, 'fail'])->name('admin.withdrawals.fail');
    Route::post('/banstech-admin/withdrawals/{withdrawal}/retry-paypal', [WithdrawalRequestController::class, 'retryPayPal'])->name('admin.withdrawals.retry-paypal');
});
