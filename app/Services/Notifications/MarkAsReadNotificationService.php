<?php

namespace App\Services\Notifications;

use App\Models\Notification;

class MarkAsReadNotificationService
{
    /**
     * Mark all notifications as read.
     *
     * @return void
     */
    public function run(): void
    {
        Notification::query()
            ->where(['user_id' => auth()->id(), 'status' => 'unread'])
            ->update(['status' => 'read']);
    }
}
