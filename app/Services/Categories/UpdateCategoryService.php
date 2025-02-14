<?php

namespace App\Services\Categories;

use App\Models\Category;
use Illuminate\Http\Request;

class UpdateCategoryService
{
    /**
     * Run the update category by id
     *
     * @param int $id
     * @param Request $request
     * @return true
     * @throws \Exception
     */
    public function run(int $id, Request $request): true
    {
        $category = Category::query()->find($id);
        $category->update($request->all());
        $category->organizations()->sync($request['organizations']);
        $category->makeRoot()->save();
        if (!empty($request['parent_id']) && $category->parent_id != $request['parent_id']) {
            $category->appendToNode(Category::query()->find($request['parent_id']))->save();
        }

        return true;
    }
}
