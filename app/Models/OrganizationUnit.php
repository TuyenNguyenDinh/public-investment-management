<?php

namespace App\Models;

use App\Models\Scopes\CheckOrganizationScope;
use App\Traits\CommonNestedSetTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Kalnoy\Nestedset\NodeTrait;

class OrganizationUnit extends Model
{
    use HasFactory;
    use NodeTrait;
    use CommonNestedSetTrait;

    protected $fillable = [
        'user_created_id',
        'name',
        'description',
        'phone_number',
        'address',
        'tax_code',
    ];

    protected $appends = ['text'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(Account::class, UsersOrganizations::class, 'organization_id', 'user_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, CategoriesOrganizationUnits::class, 'organization_id', 'category_id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, RoleOrganization::class, 'organization_id', 'role_id');
    }
}
