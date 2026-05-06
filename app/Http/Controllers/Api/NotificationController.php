<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function unRead(Request $request)
    {
        $user = $request->user();
        $notifications = $user->unreadNotifications;

        return response()->json([
            'status' => true,
            'data' => $notifications
        ]);
    }

    public function markAsRead(Request $request, int $id)
    {
        $user = $request->user();
        $notification = $user->notifications()->find($id);

        if (!$notification) {
            return response()->json([
                'status' => false,
                'message' => 'Notification not found'
            ], 404);
        }

        $notification->markAsRead();

        return response()->json([
            'status' =>  true,
            'message' => "Notification mark as unread!"
        ]);
    }
}
