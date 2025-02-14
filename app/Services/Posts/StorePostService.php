<?php

namespace App\Services\Posts;

use App\Enums\Posts\PostType;
use App\Helpers\StoragePath;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StorePostService
{
    /**
     * Handle create new a post
     *
     * @param array $params
     * @return bool
     */
    public function run(array $params): bool
    {
        $userId = auth()->id();
        $pathThumbnail = !empty($params['thumbnail'])
            ? str_replace(':userId', $userId, StoragePath::POST_THUMBNAIL)
            : null;
        DB::beginTransaction();
        try {
            if ($pathThumbnail) {
                $pathThumbnail = resolve(StoreFileService::class)->run($pathThumbnail, $params['thumbnail']);
            }

            $post = Post::create([
                'title' => $params['title'],
                'slug' => Str::slug($params['title']) . '-' . \str()->random(10),
                'created_by' => $userId,
                'updated_by' => $userId,
                'thumbnail' => $pathThumbnail ? Storage::disk('thumbnail-post')->url($pathThumbnail) : null,
                'content' => $params['content'],
                'scheduled_date' => $params['scheduled_date'],
                'status' => PostType::DRAFT,
            ]);

            if (!$post) {
                $this->rollbackFiles($pathThumbnail);
                return false;
            }

            $post->categories()->sync($params['categories'] ?? []);
            $post->organizations()->sync($params['organizations'] ?? []);

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->rollbackFiles($pathThumbnail);
            Log::debug($e);

            return false;
        }
    }

    /**
     * Rollback files
     *
     * @param string|null $pathThumbnail
     * @return void
     */
    private function rollbackFiles(?string $pathThumbnail): void
    {
        if ($pathThumbnail) {
            Storage::disk('thumbnail-post')->delete($pathThumbnail);
        }
    }
}
