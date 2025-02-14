<?php

namespace App\Services\Notifications;

use App\Models\Notification;

class GetNotificationForCurrentUserService
{
    /**
     * Get notifications for the current user.
     *
     * @return array
     */
    public function run(): array
    {
        $notifications = Notification::query()->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        $countNewNotification = Notification::query()->where('user_id', auth()->id())
            ->where('status', 'unread')
            ->count();

        return compact('notifications', 'countNewNotification');
    }
}
