<?php

namespace App\Services\Menus;

use App\Models\Menu;

class UpdateMenuService
{
    /**
     * Run the update menu by id
     *
     * @param int $id
     * @param array $requestData
     * @return void
     */
    public function run(int $id, array $requestData): void
    {
        $organization = Menu::query()->find($id);
        if (!empty($requestData['group_menu_flag']) && !empty($organization->parent_id)) {
            $requestData['parent_id'] = null;
        }

        if (!empty($requestData['group_menu_flag'])) {
            $requestData['icon'] = null;
            $requestData['slug'] = null;
            $requestData['url'] = null;
        }
        $requestData['route_name'] = !empty($requestData['route_name']) ? route($requestData['route_name']) : null;
        $organization->update($requestData);
        if (!empty($requestData['parent_id']) && $organization->parent_id != $requestData['parent_id']) {
            $organization->appendToNode(Menu::query()->find($requestData['parent_id']))->save();
        }
    }
}
