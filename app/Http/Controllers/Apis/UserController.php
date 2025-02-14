<?php

namespace App\Http\Controllers\Apis;

use App\Collections\Users\UserListCollection;
use App\Enums\BaseEnum;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Resources\Users\UserDetailResource;
use App\Services\Users\DeleteUserByIdService;
use App\Services\Users\GetUserByIdService;
use App\Services\Users\GetUserListService;
use App\Services\Users\StoreUserService;
use App\Services\Users\TriggerUserByIdService;
use App\Services\Users\UpdateUserByIdService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    /**
     * Get the user list
     *
     * @return UserListCollection
     */
    public function index(): UserListCollection
    {
        $userData = resolve(GetUserListService::class)->run();

        return $this->formatJson(UserListCollection::class, $userData);
    }

    /**
     * Get the user detail by id
     *
     * @param int $id
     * @return UserDetailResource
     */
    public function show(int $id): UserDetailResource
    {
        $user = resolve(GetUserByIdService::class)->run($id);

        return $this->formatJson(UserDetailResource::class, $user);
    }

    /**
     * Store the user
     *
     * @param StoreUserRequest $request
     * @return JsonResponse
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $request = $request->validated();
        resolve(StoreUserService::class)->run($request);
        session()->flash('success', __('user_create_success'));

        return $this->responseSuccess();
    }

    /**
     * Update the user by id
     *
     * @param int $id
     * @param UpdateUserRequest $request
     * @return JsonResponse
     */
    public function update(int $id, UpdateUserRequest $request): JsonResponse
    {
        $request = $request->only(['name', 'email', 'password', 'role', 'organizations', 'menus']);
        resolve(UpdateUserByIdService::class)->run($id, $request);
        session()->flash('success', __('user_update_success'));

        return $this->responseSuccess();
    }

    /**
     * Delete the user by id
     *
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        resolve(DeleteUserByIdService::class)->run($id);
        session()->flash('success', __('user_delete_success'));

        return $this->responseSuccess();
    }

    /**
     * Change user status
     * 
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function triggers(int $id, Request $request): JsonResponse
    {
        $request = $request->only('is_active');
        $statusMsg = BaseEnum::getActiveByStatus($request['is_active']);
        resolve(TriggerUserByIdService::class)->run($id, $request);
        if ($statusMsg === BaseEnum::ACTIVE_TEXT) {
            session()->flash('success', __('user_activate_success'));
        } else {
            session()->flash('success', __('user_deactivate_success'));
        }
        return $this->responseSuccess();
    }
}
