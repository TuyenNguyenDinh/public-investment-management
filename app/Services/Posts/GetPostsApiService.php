<?php

namespace App\Services\Posts;

use App\Enums\BaseEnum;
use App\Enums\Posts\PostType;
use App\Models\Category;
use App\Models\OrganizationUnit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class GetPostsApiService
{
    /**
     * Get posts by categories ids
     *
     * @param array $params
     * @return Collection
     */
    public function run(array $params): Collection
    {
        $organizationId = session('organization_id');
        $organizationIds = $this->getOrganizationIds($organizationId);
        $categories = $this->getCategoriesWithPosts($params, $organizationIds);

        return $this->formatPosts($categories);
    }

    /**
     * Get organization IDs based on session
     *
     * @param int|null $organizationId
     * @return array
     */
    private function getOrganizationIds(?int $organizationId): array
    {
        return OrganizationUnit::query()
            ->when($organizationId, function (Builder $q) use ($organizationId) {
                $q->where('id', $organizationId)
                    ->orWhereDescendantOf($organizationId);
            })
            ->pluck('id')
            ->toArray();
    }

    /**
     * Fetch categories and related posts based on parameters
     *
     * @param array $params
     * @param array $organizationIds
     * @return Collection
     */
    private function getCategoriesWithPosts(array $params, array $organizationIds): Collection
    {
        return Category::query()
            ->with(['posts' => fn(BelongsToMany $query) => $this->applyPostFilters($params, $query, $organizationIds)])
            ->with('organizations')
            ->when($params['category_id'], fn(Builder $q) => $q->where('id', $params['category_id']))
            ->whereHas('organizations', fn($q) => $this->applyOrganizationFilters($params['organization_id'], $q, $organizationIds))
            ->get();
    }

    /**
     * Format posts by adding categories to each post
     *
     * @param Collection $categories
     * @return Collection
     */
    private function formatPosts(Collection $categories): Collection
    {
        return $categories
            ->pluck('posts')
            ->flatten()
            ->unique('id')
            ->map(function ($post) use ($categories) {
                $post->categories = $categories->filter(function ($category) use ($post) {
                    return $category->posts->contains('id', $post->id);
                })->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                    ];
                })->values();

                return $post;
            });
    }

    /**
     * Apply filters to the posts query
     *
     * @param array $params
     * @param BelongsToMany $query
     * @param array $organizationIds
     * @return void
     */
    private function applyPostFilters(array $params, BelongsToMany $query, array $organizationIds): void
    {
        $user = auth()->user();

        $query->when($params['start_date'] && $params['end_date'], function (Builder $q) use ($params) {
            $q->whereBetween('created_at', [$params['start_date'], $params['end_date']]);
        })->when(isset($params['status']), function (Builder $q) use ($params) {
            $this->filterByStatus($q, $params['status']);
        })->filterPost()
            ->whereHas('organizations', function ($q) use ($params, $organizationIds) {
                $this->applyOrganizationFilters($params['organization_id'], $q, $organizationIds);
            })->with([
                'logsUsersViewed' => fn($q) => $q->where('user_id', $user->id),
                'creator:id,name',
                'updater:id,name',
                'organizations'
            ])->orderByDesc('created_at');
    }

    /**
     * Filter posts by status
     *
     * @param Builder $query
     * @param string $status
     * @return void
     */
    private function filterByStatus(Builder $query, string $status): void
    {
        $query->when($status === PostType::SCHEDULED, function (Builder $q) {
            $q->where('status', PostType::DRAFT)->whereNotNull('scheduled_date');
        })->when(in_array($status, [PostType::DRAFT, PostType::APPROVED]), function (Builder $q) use ($status) {
            $q->where('status', $status);
        });
    }

    /**
     * Apply organization filters to the query
     *
     * @param int|null $organizationId
     * @param Builder $query
     * @param array $organizationIds
     * @return void
     */
    private function applyOrganizationFilters(?int $organizationId, Builder $query, array $organizationIds): void
    {
        $query->when($organizationId, fn(Builder $q) => $q->where('organization_id', $organizationId))
            ->when(!$organizationId, fn(Builder $q) => $q->whereIn('organization_id', $organizationIds));
    }

    /**
     * Check if user has required permissions
     *
     * @param $user
     * @param int|null $organizationId
     * @return bool
     */
    private function userHasPermissions($user, ?int $organizationId): bool
    {
        return $user->hasOrganizationPermission(BaseEnum::POST['CREATE'], $organizationId) ||
            $user->hasOrganizationPermission(BaseEnum::POST['UPDATE'], $organizationId) ||
            $user->hasOrganizationPermission(BaseEnum::POST['REVIEW'], $organizationId);
    }
}
