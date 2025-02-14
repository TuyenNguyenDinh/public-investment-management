<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleOrganization extends Model
{
    use HasFactory;

    protected $table = 'roles_organizations';

    protected $fillable = [
        'role_id',
        'organization_id',
    ];

    public function roles(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
