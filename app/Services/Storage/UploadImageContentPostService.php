<?php

namespace App\Services\Storage;

use App\Helpers\StoragePath;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;

class UploadImageContentPostService extends ApiController
{
    /**
     * Handle upload image content post
     *
     * @param array $params
     * @return string
     */
    public function run(array $params): string
    {
        $path = StoragePath::POST_CONTENT;
        if ($params['tmp']) {
            $path = StoragePath::POST_CONTENT_TMP;
        }
        $pathImage = Storage::disk('content-post')
            ->put(str_replace([':userId', ':tmpName'], [auth()->id(), $params['tmp']], $path), $params['upload']);
        return Storage::disk('content-post')->url($pathImage);
    }
}