<?php

namespace App\Services\Menus;

use App\Models\Menu;

class GetMenuByIdService
{
   /**
    * Run the get menu by id
    *
    * @param int $id
    * @return Menu
    */
   public function run(int $id): Menu
   {
      return Menu::query()->with('parent:id')->find($id);
   }
}
