<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class NotificationController extends Controller
{
    /**
     * Display user's notifications
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Check if user has Notifiable trait
        if (!method_exists($user, 'notifications')) {
            return back()->with('error', 'User model must use Notifiable trait.');
        }
        
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $unreadCount = $user->unreadNotifications()->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        /** @var User $user */
        $user = Auth::user();
        
        $notification = $user->notifications()->find($id);
        
        if ($notification) {
            $notification->markAsRead();
        }

        return back()->with('message', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        /** @var User $user */
        $user = Auth::user();
        
        $user->unreadNotifications()->update(['read_at' => now()]);

        return back()->with('message', 'All notifications marked as read.');
    }

    /**
     * Get unread notifications count (for AJAX)
     */
    public function getUnreadCount()
    {
        /** @var User $user */
        $user = Auth::user();
        $count = $user->unreadNotifications()->count();
        
        return response()->json(['count' => $count]);
    }

    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        /** @var User $user */
        $user = Auth::user();
        
        $notification = $user->notifications()->find($id);
        
        if ($notification) {
            $notification->delete();
        }

        return back()->with('message', 'Notification deleted.');
    }
}