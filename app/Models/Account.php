<?php

namespace App\Models;

use App\Models\Scopes\CheckOrganizationScope;
use App\Traits\CheckOrganizationPermissionTrait;
use App\Traits\SetDateTimeConfigTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\HasProfilePhoto;
use Spatie\Permission\Traits\HasRoles;

class Account extends Model
{
    use HasFactory;
    use HasProfilePhoto;
    use HasRoles;
    use SetDateTimeConfigTrait;
    use CheckOrganizationPermissionTrait;

    protected $table = 'users';

    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'sex',
        'avatar',
        'date_of_birth',
        'citizen_identification',
        'front_citizen_identification_img',
        'back_citizen_identification_img',
        'phone_number',
        'hometown',
        'permanent_address',
        'temporary_address',
        'education_level',
        'health_status',
        'height',
        'weight',
        'is_active',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function dateOfBirth(): Attribute
    {
        $dateFormat = app()->getLocale() === 'vn' ? 'd-m-Y' : 'Y-m-d';

        return Attribute::make(
            get: fn($value) => date($dateFormat, strtotime($value)),
        );
    }

    protected static function booted(): void
    {
        if (Auth::check()) {
            static::addGlobalScope(new CheckOrganizationScope());
        }
    }

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(OrganizationUnit::class, UsersOrganizations::class, 'user_id', 'organization_id');
    }

    public function relatives(): HasMany
    {
        return $this->hasMany(Relative::class, 'user_id');
    }

    public function educations(): HasMany
    {
        return $this->hasMany(Education::class, 'user_id');
    }

    public function workHistories(): HasMany
    {
        return $this->hasMany(WorkHistory::class, 'user_id');
    }

    /**
     * Define a many-to-many relationship with the Menu model
     * through the UserMenu pivot table.
     *
     * @return BelongsToMany
     */
    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class, UserMenu::class, 'user_id', 'menu_id');
    }
}
