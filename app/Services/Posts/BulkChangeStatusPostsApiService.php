<?php

namespace App\Services\Posts;

use App\Enums\Posts\PostType;
use App\Models\Notification;
use App\Models\Post;
use App\Models\PostApprovalLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BulkChangeStatusPostsApiService
{
    /**
     * Bulk change status for posts.
     *
     * @param array $data
     * @return void
     * @throws \Exception
     */
    public function run(array $data): void
    {
        $userId = auth()->id();
        $status = $data['status'];
        $note = $data['note'];
        $time = now()->toDateTimeString();
        $postIds = $data['post_ids'] ?? [];

        DB::beginTransaction();
        try {
            // Update post statuses
            $this->updatePostStatuses($postIds, $status, $userId);

            // Log post approval changes
            $this->logPostApprovalChanges($postIds, $userId, $status, $time, $note);

            // Create notifications
            $this->createNotifications($postIds, $status, $time, $note);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('BulkChangeStatusPostsApiService failed', [
                'data' => $data,
                'exception' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Update the statuses of the given posts.
     *
     * @param array $postIds
     * @param int $status
     * @param int $userId
     * @return void
     */
    protected function updatePostStatuses(array $postIds, int $status, int $userId): void
    {
        Post::query()
            ->whereIn('id', $postIds)
            ->update([
                'status' => $status,
                'updated_by' => $userId,
            ]);
    }

    /**
     * Log the post approval changes.
     *
     * @param array $postIds
     * @param int $userId
     * @param int $status
     * @param string $time
     * @param string|null $note
     * @return void
     */
    protected function logPostApprovalChanges(array $postIds, int $userId, int $status, string $time, ?string $note = 'dump note'): void
    {
        $logBuilder = Post::query()
            ->select([
                'id as post_id',
                DB::raw("'{$userId}' as user_id"),
                DB::raw("'{$status}' as status"),
                DB::raw("'{$note}' as note"),
                DB::raw("'{$time}' as created_at"),
                DB::raw("'{$time}' as updated_at"),
            ])
            ->whereIn('id', $postIds);

        PostApprovalLog::query()->insertUsing(
            ['post_id', 'user_id', 'status', 'note', 'created_at', 'updated_at'],
            $logBuilder
        );
    }

    /**
     * Create notifications for the given posts.
     *
     * @param array $postIds
     * @param int $status
     * @param string $time
     * @param string|null $note
     * @return void
     */
    protected function createNotifications(array $postIds, int $status, string $time, ?string $note = 'dump note'): void
    {
        $notificationData = $this->prepareNotificationData($status);

        $notificationBuilder = Post::query()
            ->select([
                'id as target_id',
                DB::raw('created_by as user_id'),
                DB::raw("'App\\\\Models\\\\Post' as target_type"),
                DB::raw("'unread' as status"),
                DB::raw("'{$notificationData['title']}' as title"),
                DB::raw("'{$notificationData['content']}' as content"),
                DB::raw("'{$notificationData['title_en']}' as title_en"),
                DB::raw("'{$notificationData['content_en']}' as content_en"),
                DB::raw("'{$note}' as metadata"),
                DB::raw("'{$time}' as created_at"),
                DB::raw("'{$time}' as updated_at"),
            ])
            ->whereIn('id', $postIds);

        Notification::query()->insertUsing(
            ['target_id', 'user_id', 'target_type', 'status', 'title', 'content', 'title_en', 'content_en', 'metadata', 'created_at', 'updated_at'],
            $notificationBuilder
        );
    }

    /**
     * Prepare notification data based on status.
     *
     * @param int|null $status
     * @return array
     */
    protected function prepareNotificationData(?int $status): array
    {
        $messages = [
            PostType::APPROVED => ['post_approved', 'post_approved_content'],
            PostType::SCHEDULED => ['post_scheduled', 'post_scheduled_content'],
            PostType::SUBMITTED => ['post_submitted', 'post_submitted_content'],
            PostType::REJECTED => ['post_rejected', 'post_rejected_content'],
            PostType::LOCKED => ['post_locked', 'post_locked_content'],
            'default' => ['post_draft', 'post_draft_content'],
        ];

        [$titleKey, $contentKey] = $messages[$status] ?? $messages['default'];

        return [
            'title' => __($titleKey, locale: 'vn'),
            'content' => __($contentKey, locale: 'vn'),
            'title_en' => __($titleKey, locale: 'en'),
            'content_en' => __($contentKey, locale: 'en'),
        ];
    }
}
