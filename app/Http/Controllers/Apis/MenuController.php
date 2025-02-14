<?php

namespace App\Http\Controllers\Apis;

use App\Collections\Menus\MenuListCollection;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Menus\StoreMenuRequest;
use App\Http\Requests\Menus\UpdateMenuRequest;
use App\Resources\Menus\MenuDetailResource;
use App\Services\Menus\BulkUpdateMenuService;
use App\Services\Menus\DeleteMenuByIdService;
use App\Services\Menus\GetMenuByIdService;
use App\Services\Menus\GetMenuListService;
use App\Services\Menus\StoreMenuService;
use App\Services\Menus\UpdateMenuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuController extends ApiController
{
    /**
     * Get the menu list
     *
     * @return MenuListCollection
     */
    public function index(): MenuListCollection
    {
        $tree = resolve(GetMenuListService::class)->run();

        return $this->formatJson(MenuListCollection::class, $tree);
    }

    /**
     * Store the menu
     *
     * @param StoreMenuRequest $request
     * @return JsonResponse
     */
    public function store(StoreMenuRequest $request): JsonResponse
    {
        $request = $request->validated();
        resolve(StoreMenuService::class)->run($request);
        session()->flash('success', __('menu_create_success'));  // Use translation key

        return $this->responseSuccess();
    }

    /**
     * Get the menu by id
     *
     * @param int $id
     * @return MenuDetailResource
     */
    public function show(int $id): MenuDetailResource
    {
        $tree = resolve(GetMenuByIdService::class)->run($id);

        return $this->formatJson(MenuDetailResource::class, $tree);
    }

    /**
     * Update the menu by id
     *
     * @param int $id
     * @param UpdateMenuRequest $request
     * @return JsonResponse
     */
    public function update(int $id, UpdateMenuRequest $request): JsonResponse
    {
        $request = $request->validated();
        resolve(UpdateMenuService::class)->run($id, $request);
        session()->flash('success', __('menu_update_success'));  // Use translation key

        return $this->responseSuccess();
    }

    public function bulkUpdate(Request $request): JsonResponse
    {
        $request = $request->only('new_tree');
        resolve(BulkUpdateMenuService::class)->run($request);
        session()->flash('success', __('menu_update_position_success'));  // Use translation key

        return $this->responseSuccess();
    }

    /**
     * Delete the menu by id
     *
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        resolve(DeleteMenuByIdService::class)->run($id);
        session()->flash('success', __('menu_delete_success'));  // Use translation key

        return $this->responseSuccess();
    }
}
