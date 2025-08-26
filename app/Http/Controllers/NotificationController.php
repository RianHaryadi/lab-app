<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        Log::info('Marking notification as read', [
            'notification_id' => $id,
            'user_id' => Auth::id(),
        ]);

        try {
            $notification = Auth::user()->notifications()->findOrFail($id);
            $notification->markAsRead();
            Log::info('Notification marked as read successfully', ['notification_id' => $id]);
        } catch (\Exception $e) {
            Log::error('Failed to mark notification as read', [
                'notification_id' => $id,
                'error' => $e->getMessage(),
            ]);
            return redirect()->route('dashboard')->with('error', 'Gagal menandai notifikasi sebagai sudah dibaca.');
        }

        return redirect()->route('dashboard');
    }
}