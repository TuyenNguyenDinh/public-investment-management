<?php

namespace App\Services\Posts;

use App\Models\OrganizationUnit;
use App\Services\Organizations\GetAllOrganizationUnitsService;

class CreatePostViewService
{
    /**
     * Handle view create post page
     *
     * @return array
     */
    public function run(): array
    {
        $organizations = resolve(GetAllOrganizationUnitsService::class)->run();
        $categories = OrganizationUnit::with('categories')->get()->pluck('categories')
            ->flatten()
            ->unique('id');

        return [
            'organizations' => $organizations,
            'categories' => $categories
        ];
    }
}
