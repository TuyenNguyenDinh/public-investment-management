<?php

namespace App\Services\Categories;

use App\Models\CategoriesOrganizationUnits;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StoreCategoryService
{
    /**
     * @throws \Exception
     */
    public function run(Request $request): true
    {
        $userId = auth()->id();
        DB::beginTransaction();
        try {
            $requestData = [
                'name' => $request->input('name'),
                'parent_id' => $request->input('parent_id'),
                'organizations' => $request->input('organizations'),
                'created_by' => $userId,
                'updated_by' => $userId,
            ];

            $category = Category::query()->create($requestData);
            CategoriesOrganizationUnits::create([
                'organization_id' => $request['organizations'],
                'category_id' => $category->id,
            ]);
            session()->flash('success', __('category_create_success'));

            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::debug($e);
            DB::rollBack();
            throw $e;
        }
    }
}
