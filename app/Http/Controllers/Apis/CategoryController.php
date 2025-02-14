<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Categories\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\Categories\StoreCategoryService;
use App\Services\Categories\UpdateCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    public function index()
    {
        return Category::query()->get()->toTree();
    }

    public function tree()
    {
        return Category::query()
            ->get()
            ->toTree();
    }

    public function store(Request $request): JsonResponse
    {
        $storeCategory = resolve(StoreCategoryService::class)->run($request);

        if ($storeCategory) {
            session()->flash('success', __('category_create_success'));
            return $this->responseSuccess();
        } else {
            session()->flash('error', __('error_occurred'));
            return $this->responseError('Failed to store category');
        }
    }

    public function show(int $id)
    {
        return Category::query()->with(['parent:id', 'organizations'])->find($id);
    }

    public function update(int $id, UpdateCategoryRequest $request): JsonResponse
    {
        $updateCategory = resolve(UpdateCategoryService::class)->run($id, $request);

        if ($updateCategory) {
            session()->flash('success', __('category_update_success'));
            return $this->responseSuccess();
        } else {
            session()->flash('error', __('error_occurred'));
            return $this->responseError('Failed to store category');
        }
    }

    public function delete(int $id): JsonResponse
    {
        Category::query()->find($id)->delete();
        session()->flash('success', __('category_delete_success'));

        return $this->responseSuccess();
    }
}
