<?php

namespace App\Models;

use App\Models\Scopes\CheckOrganizationScope;
use App\Traits\SetDateTimeConfigTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory;
    use SetDateTimeConfigTrait;

    protected static function booted(): void
    {
        if (Auth::check()) {
            static::addGlobalScope(new CheckOrganizationScope());
        }
    }

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(OrganizationUnit::class, RoleOrganization::class, 'role_id', 'organization_id');
    }
}
