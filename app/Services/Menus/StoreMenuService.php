<?php

namespace App\Services\Menus;

use App\Models\Menu;

class StoreMenuService
{
    /**
     * Run the store menu tree
     *
     * @param array $requestData
     * @return void
     */
    public function run(array $requestData): void
    {
        /** @var Menu $unit */
        $parentId = $requestData['parent_id'] ?? null;
        $requestData['route_name'] = !empty($requestData['route_name']) ? route($requestData['route_name']) : null;
        $unit = Menu::create($requestData);
        if ($parentId) {
            $unit->appendToNode(Menu::find($parentId))->save();
        }
    }
}
