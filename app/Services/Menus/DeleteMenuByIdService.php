<?php

namespace App\Services\Menus;

use App\Models\Menu;

class DeleteMenuByIdService
{
   /**
    * Run the delete menu by id
    *
    * @param int $id
    * @return void
    */
   public function run(int $id): void
   {
      Menu::query()->find($id)->delete();
   }
}
