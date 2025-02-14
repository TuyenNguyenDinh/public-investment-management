<?php

namespace App\Services\Categories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class GetAllCategoriesService
{
   /**
    * Run the get categories
    *
    * @return Collection
    */
   public function run(): Collection
   {
      return Category::query()
         ->get();
   }
}
