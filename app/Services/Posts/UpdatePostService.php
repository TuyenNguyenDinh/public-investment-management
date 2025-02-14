<?php

namespace App\Services\Posts;

use App\Helpers\StoragePath;
use App\Models\Configuration;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UpdatePostService
{
    public function run(array $params, string $slug): bool
    {
        $userId = auth()->id();
        $post = Post::where('slug', $slug)->firstOrFail();

        $pathThumbnail = !empty($params['thumbnail'])
            ? str_replace(':userId', $userId, StoragePath::POST_THUMBNAIL)
            : null;
        DB::beginTransaction();
        try {
            if ($pathThumbnail) {
                $pathThumbnail = resolve(StoreFileService::class)->run($pathThumbnail, $params['thumbnail']);
                $params['thumbnail'] = Storage::disk('thumbnail-post')->url($pathThumbnail);
            } else {
                $params['thumbnail'] = $post->thumbnail;
            }
            $post->update($params);

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
     * @param string $pathThumbnail
     * @return void
     */
    private function rollbackFiles(string $pathThumbnail): void
    {
        Storage::disk('thumbnail-post')->delete($pathThumbnail);
    }
}
