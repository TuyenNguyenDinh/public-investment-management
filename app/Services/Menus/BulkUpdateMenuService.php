<?php

namespace App\Services\Menus;

use App\Models\Menu;

class BulkUpdateMenuService
{
   /**
    * Run the bulk update menu
    *
    * @param array $requestData
    * @return void
    */
   public function run(array $requestData): void
   {
      Menu::rebuildTree($requestData['new_tree']);
   }
}
