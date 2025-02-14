<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Posts\StorageUploadThumbnailPostRequest;
use App\Services\Storage\UploadImageContentPostService;
use Illuminate\Http\JsonResponse;

class StorageController extends ApiController
{
    /**
     * Upload content image post
     *
     * @param StorageUploadThumbnailPostRequest $request
     * @return JsonResponse
     */
    public function uploadImageContentPost(StorageUploadThumbnailPostRequest $request): JsonResponse
    {
        $params = [
            'upload' => $request['upload'],
            'tmp' => $request['tmp'],
        ];
        $image = resolve(UploadImageContentPostService::class)->run($params);
        return $image ? $this->responseSuccessWithData($image) : $this->responseError('Upload thumbnail failed.');
    }
}