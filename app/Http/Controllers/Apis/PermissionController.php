<?php

namespace App\Http\Controllers\Apis;

use App\Collections\Permissions\PermissionListCollection;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Permissions\StoreChildPermissionRequest;
use App\Http\Requests\Permissions\StorePermissionRequest;
use App\Http\Requests\Permissions\UpdatePermissionRequest;
use App\Services\Permissions\GetPermissionService;
use App\Services\Permissions\StoreChildPermissionService;
use App\Services\Permissions\StorePermissionService;
use App\Services\Permissions\UpdatePermissionByIdService;
use Illuminate\Http\JsonResponse;

class PermissionController extends ApiController
{
    /**
     * Get the permission list
     *
     * @return PermissionListCollection
     */
    public function index(): PermissionListCollection
    {
        $permissionList = resolve(GetPermissionService::class)->run();

        return $this->formatJson(PermissionListCollection::class, $permissionList);
    }

    /**
     * Store the child permission
     *
     * @param StoreChildPermissionRequest $request
     * @return JsonResponse
     */
    public function storeChildren(StoreChildPermissionRequest $request): JsonResponse
    {
        $request = $request->validated();
        resolve(StoreChildPermissionService::class)->run($request);
        session()->flash('success', __('permission_create_success'));

        return $this->responseSuccess();
    }

    /**
     * Store the permission
     *
     * @param StorePermissionRequest $request
     * @return JsonResponse
     */
    public function store(StorePermissionRequest $request): JsonResponse
    {
        $request = $request->validated();
        resolve(StorePermissionService::class)->run($request);
        session()->flash('success', __('permission_create_success'));

        return $this->responseSuccess();
    }

    /**
     * Update the permission by id
     *
     * @param int $id
     * @param UpdatePermissionRequest $request
     * @return JsonResponse
     */
    public function update(int $id, UpdatePermissionRequest $request): JsonResponse
    {
        $request = $request->validated();
        resolve(UpdatePermissionByIdService::class)->run($id, $request);
        session()->flash('success', __('permission_update_success'));

        return $this->responseSuccess();
    }
}
