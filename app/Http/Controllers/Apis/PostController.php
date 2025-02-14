<?php

namespace App\Http\Controllers\Apis;

use App\Collections\Posts\ListPostsCollection;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Posts\BulkChangeStatusPostsApiRequest;
use App\Http\Requests\Posts\BulkDeletePostsApiRequest;
use App\Http\Requests\Posts\GetPostsApiRequest;
use App\Http\Requests\Posts\StoreAndClonePostRequest;
use App\Http\Requests\Posts\StorePostRequest;
use App\Services\Posts\BulkChangeStatusPostsApiService;
use App\Services\Posts\BulkDeletePostsApiService;
use App\Services\Posts\GetPostsApiService;
use App\Services\Posts\StorePostService;
use Illuminate\Http\JsonResponse;

class PostController extends ApiController
{
    /**
     * Get posts
     *
     * @param GetPostsApiRequest $request
     * @return ListPostsCollection
     */
    public function index(GetPostsApiRequest $request): ListPostsCollection
    {
        $params = [
            'organization_id' => $request['organization_id'],
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
            'status' => $request['status'],
            'category_id' => $request['category_id'],
        ];

        $posts = resolve(GetPostsApiService::class)->run($params);

        return $this->formatJson(ListPostsCollection::class, $posts);
    }

    /**
     * Bulk delete
     *
     * @param BulkDeletePostsApiRequest $request
     * @return JsonResponse
     */
    public function bulkDelete(BulkDeletePostsApiRequest $request): JsonResponse
    {
        $result = resolve(BulkDeletePostsApiService::class)->run($request['post_ids'] ?? []);

        return $result ? $this->responseSuccess() : $this->responseError('Delete failed');
    }

    /**
     * Bulk change status
     *
     * @param BulkChangeStatusPostsApiRequest $request
     * @return JsonResponse
     */
    public function bulkChangeStatus(BulkChangeStatusPostsApiRequest $request): JsonResponse
    {
        resolve(BulkChangeStatusPostsApiService::class)->run($request->validated());

        return $this->responseSuccess();
    }

    public function store(StoreAndClonePostRequest $request): JsonResponse
    {
        $params = [
            'title' => $request['title'],
            'thumbnail' => $request['thumbnail'],
            'content' => $request['content'],
            'categories' => $request['categories'] ?? [],
            'organizations' => $request['organizations'] ?? [],
            'scheduled_date' => $request['scheduled_date'],
        ];
        $result = resolve(StorePostService::class)->run($params);

        return $result ? $this->responseSuccess() : $this->responseError('Clone failed');
    }
}
