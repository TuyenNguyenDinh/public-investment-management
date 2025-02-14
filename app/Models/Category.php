<?php

namespace App\Models;

use App\Models\Scopes\CheckOrganizationScope;
use App\Traits\CommonNestedSetTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
    use HasFactory, SoftDeletes, NodeTrait, CommonNestedSetTrait;

    protected $fillable = [
        'id',
        'name',
        'created_by',
        'updated_by',
        'parent_id',
    ];

    protected $appends = ['text'];

    protected $hidden = [
        'deleted_at',
    ];

    protected static function booted(): void
    {
        if (Auth::check()) {
            $organizationId = request()->input('organization_id');
            static::addGlobalScope(new CheckOrganizationScope($organizationId));
        }
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, PostsCategories::class, 'category_id', 'post_id');
    }

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(OrganizationUnit::class, CategoriesOrganizationUnits::class, 'category_id', 'organization_id')
            ->withPivot('organization_id');
    }
}
