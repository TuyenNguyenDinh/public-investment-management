<?php

namespace App\Http\Controllers\Apis;

use App\Collections\Roles\RoleListCollection;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Roles\StoreRoleRequest;
use App\Http\Requests\Roles\UpdateRoleRequest;
use App\Resources\Roles\RoleDetailResource;
use App\Services\Roles\DeleteRoleByIdService;
use App\Services\Roles\GetRoleByIdService;
use App\Services\Roles\GetRoleListService;
use App\Services\Roles\StoreRoleService;
use App\Services\Roles\UpdateRoleByIdService;
use Illuminate\Http\JsonResponse;

class RoleController extends ApiController
{
    /**
     * Get the role list
     *
     * @return RoleListCollection
     */
    public function index(): RoleListCollection
    {
        $userData = resolve(GetRoleListService::class)->run();

        return $this->formatJson(RoleListCollection::class, $userData);
    }

    /**
     * Store the role
     *
     * @param StoreRoleRequest $request
     * @return JsonResponse
     */
    public function store(StoreRoleRequest $request): JsonResponse
    {
        $request = $request->validated();
        resolve(StoreRoleService::class)->run($request);
        session()->flash('success', __('role_create_successfully'));

        return $this->responseSuccess();
    }

    /**
     * Get the role by id
     *
     * @param int $id
     * @return RoleDetailResource
     */
    public function show(int $id): RoleDetailResource
    {
        $user = resolve(GetRoleByIdService::class)->run($id);

        return $this->formatJson(RoleDetailResource::class, $user);
    }

    /**
     * Update the role by id
     *
     * @param int $id
     * @param UpdateRoleRequest $request
     * @return JsonResponse
     */
    public function update(int $id, UpdateRoleRequest $request): JsonResponse
    {
        $request = $request->validated();
        resolve(UpdateRoleByIdService::class)->run($id, $request);
        session()->flash('success', __('role_update_successfully'));

        return $this->responseSuccess();
    }

    /**
     * Delete the role by id
     *
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        resolve(DeleteRoleByIdService::class)->run($id);
        session()->flash('success', __('role_delete_successfully'));

        return $this->responseSuccess();
    }
}
