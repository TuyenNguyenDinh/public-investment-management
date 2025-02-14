<?php

namespace App\Models;

use App\Models\Scopes\CheckOrganizationScope;
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
            get: fn ($value) => date($dateFormat, strtotime($value)),
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
    
    public function hasOrganizationPermission(string $permission, $organizationId): bool
    {
        // 1. Lấy danh sách các tổ chức cha và chính tổ chức cần kiểm tra quyền, bỏ qua global scope
        $organizationAncestors = [];
        if (is_array($organizationId)) {
            foreach ($organizationId as $id) {
                $orgs = OrganizationUnit::withoutGlobalScopes()
                    ->ancestorsAndSelf($id)
                    ->pluck('id')
                    ->toArray();
                $organizationAncestors = array_merge($organizationAncestors, $orgs);
            }
        } else {
            $organizationAncestors = OrganizationUnit::withoutGlobalScopes()
                ->ancestorsAndSelf($organizationId)
                ->pluck('id')
                ->toArray();
        }
        // 2. Truy vấn các vai trò của người dùng có liên kết với các tổ chức trong nhánh này, bỏ qua global scope
        $userRoles = Auth::user()->roles()
            ->withoutGlobalScopes()
            ->whereHas('organizations', function ($query) use ($organizationAncestors) {
                $query->whereIn('organization_id', $organizationAncestors);
            })
            ->get();
        // 3. Kiểm tra quyền trên từng vai trò của người dùng
        foreach ($userRoles as $role) {
            if ($role->checkPermissionTo($permission)) {
                return true; // Người dùng có quyền cần thiết trong một trong các vai trò
            }
        }

        return false; // Người dùng không có quyền cần thiết
    }

    public function getAllOrganizationTrees()
    {
        // Lấy danh sách ID của tất cả các tổ chức mà user này thuộc về
        $organizationIds = $this->organizations()->withoutGlobalScopes()->pluck('organization_units.id');

        // Lấy chính nó và tất cả con cháu của từng tổ chức trong danh sách
        return OrganizationUnit::query()->withoutGlobalScopes()
            ->whereIn('id', $organizationIds)
            ->orWhereHas('ancestors', function ($query) use ($organizationIds) {
                $query->whereIn('id', $organizationIds);
            })
            ->get()
            ->toTree();
    }
}
