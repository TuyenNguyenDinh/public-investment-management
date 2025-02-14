<?php

namespace App\Imports;

use App\Enums\Posts\PostType;
use App\Models\Category;
use App\Models\OrganizationUnit;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Ramsey\Uuid\Uuid;

class PostsImport implements ToModel, WithHeadingRow
{
    /**
     * Transform a row from the Excel file into a usable format.
     *
     * @param array $row
     *
     * @return Model|null
     */
    public function model(array $row): ?Model
    {
        DB::beginTransaction();
        try {
            $userId = auth()->id();
            $slug = Str::slug($row['title'] . '-' . Uuid::uuid4()->toString());
            $categoryIds = [];
            $organizationIds = [];
            $categoryNames = explode('|', $row['categories']);
            foreach ($categoryNames as $categoryName) {
                /** @var Category $category */
                $category = Category::where('name', $categoryName)->first();
                if ($category) {
                    $categoryIds[] = $category->id;
                }
            }
            $organizationNames = explode('|', $row['organizations']);

            foreach ($organizationNames as $organizationName) {
                /** @var OrganizationUnit $organization */
                $organization = OrganizationUnit::where('name', $organizationName)->first();
                if ($organization) {
                    $organizationIds[] = $organization->id;
                }
            }

            $post = Post::create([
                'title' => $row['title'],
                'slug' => $slug,
                'content' => $row['content'],
                'created_by' => $userId,
                'updated_by' => $userId,
                'status' => PostType::DRAFT,
                'thumbnail' => $row['thumbnail'] ?? null,
            ]);
            if ($categoryIds) {
                $post->categories()->sync($categoryIds);
            }
            if ($organizationIds) {
                $post->organizations()->sync($organizationIds);
            }

            DB::commit();
            return $post;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e);
            return null;
        }
    }
}
