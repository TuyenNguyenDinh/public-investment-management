<?php

namespace App\Models;

use App\Enums\BaseEnum;
use App\Enums\Posts\PostType;
use App\Models\Scopes\CheckOrganizationScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'title',
        'slug',
        'thumbnail',
        'content',
        'status',
        'views',
        'created_by',
        'updated_by',
        'scheduled_date'
    ];

    protected $hidden = [
        'deleted_at',
    ];

    protected static function booted(): void
    {
        if (Auth::check()) {
            static::addGlobalScope(new CheckOrganizationScope());
        }
    }

    public function scheduledDate(): Attribute
    {
        $locale = app()->getLocale();

        return new Attribute(get: fn($value) => $locale === 'vn'
            ? Carbon::parse($value)->format('d/m/Y H:i')
            : Carbon::parse($value)->format('Y/m/d H:i')
        );
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, PostsCategories::class, 'post_id', 'category_id');
    }

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(OrganizationUnit::class, PostsOrganizations::class, 'post_id', 'organization_id');
    }

    public function logsUsersViewed(): BelongsToMany
    {
        return $this->belongsToMany(User::class, LogUserViewedPost::class, 'post_id', 'user_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeFilterPost(Builder $query): void
    {
        $user = auth()->user();
        $organizationId = session('organization_id');
        $query
            ->when($this->userHasPermissions($user, $organizationId)
                && !$user->hasRole('Admin'), function (Builder $q) use ($user, $organizationId) {
                $q->when(!$user->hasOrganizationPermission(BaseEnum::POST['REVIEW'], $organizationId), function (Builder $q) use ($user) {
                    $q->whereIn('status', [PostType::APPROVED, PostType::LOCKED])->orWhere('created_by', $user->id);
                });
            })->when(!$this->userHasPermissions($user, $organizationId), function (Builder $q) {
                $q->whereIn('status', [PostType::APPROVED, PostType::SCHEDULED]);
            })
            ->whereHas('categories');
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
