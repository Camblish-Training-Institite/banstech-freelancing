<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationsController extends Controller
{
    /**
     * Fetch all notifications for the authenticated user.
     */
    public function index()
    {
        $user = Auth::user();
        return response()->json([ 
            'read' => $user->readNotifications,
            'unread' => $user->unreadNotifications,
        ]);
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(int $id)
    {
        $notification = Notification::findOrFail($id);
        $notification->markAsRead();
        return redirect()->back();
    }

        /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back();
    }
}
