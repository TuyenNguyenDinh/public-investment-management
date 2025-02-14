<?php

namespace App\Http\Controllers\Apis;

use App\Collections\Notifications\NotificationListCollection;
use App\Http\Controllers\ApiController;
use App\Services\Notifications\GetNotificationForCurrentUserService;
use App\Services\Notifications\MarkAsReadNotificationService;
use Illuminate\Http\JsonResponse;

class NotificationController extends ApiController
{
    /**
     * Get notifications for the current user.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $data = resolve(GetNotificationForCurrentUserService::class)->run();

        return $this->responseSuccessWithData([
            'notifications' => $this->formatJson(NotificationListCollection::class, $data['notifications']),
            'count' => $data['countNewNotification']
                ? __('notification_count_new', ['count' => $data['countNewNotification']])
                : 0,
        ]);
    }

    /**
     * Mark all notifications as read.
     *
     * @return JsonResponse
     */
    public function markAsRead(): JsonResponse
    {
        resolve(MarkAsReadNotificationService::class)->run();

        return $this->responseSuccess();
    }
}
