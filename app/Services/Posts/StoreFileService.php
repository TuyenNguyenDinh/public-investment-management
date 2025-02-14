<?php

namespace App\Services\Posts;

use App\Models\Configuration;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Psr\Http\Message\StreamInterface;

class StoreFileService
{
    /**
     * Store a thumbnail
     *
     * @param string $pathThumbnail a path which will be used to store a thumbnail
     * @param StreamInterface|File|UploadedFile|string $content a content of thumbnail
     * @return string|bool true if the thumbnail was stored successfully, false otherwise
     */
    public function run(string $pathThumbnail, StreamInterface|File|UploadedFile|string $content): string|bool
    {
        try {
            $thumbnailSize = Configuration::query()->whereIn('key', [
                'thumbnail_post_width_size', 'thumbnail_post_height_size'
            ])->pluck('value', 'key')->toArray();

            $tempPathThumbnail = Storage::disk('thumbnail-post')->put($pathThumbnail, $content);
            $imageManager = ImageManager::gd();
            $thumbnail = $imageManager->read('storage/posts/thumbnail/' . $tempPathThumbnail);
            $thumbnail->scaleDown($thumbnailSize['thumbnail_post_width_size'] ?? 40, $thumbnailSize['thumbnail_post_height_size'] ?? 40);
            $thumbnail->save(public_path('storage/posts/thumbnail/' . $tempPathThumbnail));

            return $tempPathThumbnail;
        } catch (\Throwable $th) {
            Log::debug($th);
            return false;
        }
    }
}
