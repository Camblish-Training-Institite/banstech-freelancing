<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationsController;

// ... your other routes

Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationsController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-as-read', [NotificationsController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationsController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

    Route::get('/notification/mark-as-read/{id}', [NotificationsController::class, 'markAsRead'])->name('notifications.markAsReadSingle');
});

// It's important that your broadcasting routes are authenticated.
// This is handled automatically by the BroadcastServiceProvider,
// which creates the /broadcasting/auth endpoint.

