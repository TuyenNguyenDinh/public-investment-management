<?php

namespace App\Services\Posts;

use App\Enums\Posts\PostType;
use App\Models\Account;
use App\Models\Category;
use App\Models\Post;
use App\Services\Organizations\GetAllOrganizationUnitsService;

class GetPostsPageViewService
{
    /**
     * Get posts page view data.
     *
     * @return array
     */
    public function run(): array
    {
        $organizations = resolve(GetAllOrganizationUnitsService::class)->run();
        $categoryCount = Category::query() ->with(['posts' => function ($query) {
            return $query->with('organizations', 'categories')->whereHas('organizations')->whereHas('categories');
        }])->count();
        $totalPosts = Post::query()
            ->filterPost()->count('id');
        $totalViews = Post::query()
            ->filterPost()->sum('views');
        $totalPostsPublished = Post::query()
            ->filterPost()->where('status', PostType::APPROVED)
            ->count('id');

        return [
            'organizations' => $organizations,
            'totalPosts' => $totalPosts,
            'totalViews' => $totalViews,
            'totalCategories' => $categoryCount,
            'totalPostsPublished' => $totalPostsPublished,
        ];
    }
}
