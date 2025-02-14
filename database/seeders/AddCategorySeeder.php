<?php

namespace Database\Seeders;

use App\Models\CategoriesOrganizationUnits;
use App\Models\Category;
use App\Models\OrganizationUnit;
use Illuminate\Database\Seeder;

class AddCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::query()->truncate();
        $organization = OrganizationUnit::firstOrFail();
        $requestData = [
            'name' => 'Category 1',
            'parent_id' => null,
            'created_by' => 1,
            'updated_by' => 1,
        ];
        $category = Category::query()->create($requestData);
        CategoriesOrganizationUnits::create([
            'organization_id' => $organization->id,
            'category_id' => $category->id,
        ]);
    }
}
