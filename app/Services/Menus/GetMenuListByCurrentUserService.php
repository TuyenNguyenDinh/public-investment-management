<?php

namespace App\Services\Menus;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Collection;

class GetMenuListByCurrentUserService
{
    /**
     * Run the get menu tree
     *
     * @return Collection
     */
    public function run(): Collection
    {
        return Menu::query()
            ->orderBy('_lft')
            ->orderBy('_rgt')
            ->get()->toTree();
    }
}
