<?php

namespace App\Models;

use App\Traits\MediaTrait;
use App\Traits\SetDateTimeConfigTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use SetDateTimeConfigTrait;
    use MediaTrait;

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
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
        'avatar_url',
        'current_organization',
        'front_identification_img_url',
        'back_identification_img_url',
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

    /**
     * @return Attribute
     */
    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getFileUrl($this->avatar)
        );
    }

    /**
     * @return Attribute
     */
    protected function frontIdentificationImgUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getFileUrl($this->front_citizen_identification_img)
        );
    }

    /**
     * @return Attribute
     */
    protected function backIdentificationImgUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getFileUrl($this->back_citizen_identification_img)
        );
    }

    /**
     * @return Attribute
     */
    protected function currentOrganization(): Attribute
    {
        $organization = OrganizationUnit::query()
            ->withoutGlobalScopes()
            ->find(session('organization_id'));

        return Attribute::make(
            get: fn () => $organization ? switchFieldByLang($organization->name, $organization->name_en) : null
        );
    }

    /**
     * @return Attribute
     */
    public function dateOfBirth(): Attribute
    {
        $dateFormat = app()->getLocale() === 'vn' ? 'd-m-Y' : 'Y-m-d';

        return Attribute::make(
            get: fn ($value) => date($dateFormat, strtotime($value)),
        );
    }
    /**
     * @return BelongsToMany
     */
    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(OrganizationUnit::class, UsersOrganizations::class, 'user_id', 'organization_id');
    }

    /**
     * @return BelongsToMany
     */
    public function organizationGroups()
    {
        return $this->belongsToMany(OrganizationUnit::class, UsersOrganizations::class);
    }

    /**
     * @return HasMany
     */
    public function relatives(): HasMany
    {
        return $this->hasMany(Relative::class);
    }

    /**
     * @return HasMany
     */
    public function educations(): HasMany
    {
        return $this->hasMany(Education::class);
    }

    /**
     * @return HasMany
     */
    public function workHistories(): HasMany
    {
        return $this->hasMany(WorkHistory::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Define a many-to-many relationship with the Menu model
     * through the UserMenu pivot table.
     *
     * @return BelongsToMany
     */
    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class, UserMenu::class);
    }

    /**
     * @param string $permission
     * @param $organizationId
     * @return bool
     */
    public function hasOrganizationPermission(string $permission, $organizationId): bool
    {
        // Check to see if the logged in organization is assigned to the user.
        // This case occurs when the currently logged-in user has their permissions updated by the admin.
        $requestOrganization = OrganizationUnit::query()->find(session('organization_id'));
        $assignedOrganizations = $this->getAllAssignedOrganizationIds();
        if (!in_array($requestOrganization->id, $assignedOrganizations)) {
            return false;
        }

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
        $userRoles = Auth::guard('web')->user()->roles()
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

    /**
     * @return mixed
     */
    public function getAllOrganizationTrees(): mixed
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

    /**
     * Get all assigned organization IDs.
     *
     * @return array
     */
    public function getAllAssignedOrganizationIds(): array
    {
        $ids = [];
        $assignedOrganizations = $this->getAllOrganizationTrees();
        foreach ($assignedOrganizations as $assignedOrganization) {
            // pluck grand children ids
            $grandIds = [];
            foreach ($assignedOrganization->children as $child) {
                $grandIds = array_merge($grandIds, $child?->children->pluck('id')->toArray() ?? []);
            }

            $ids = array_merge($ids, [
                $assignedOrganization->id,
                ...$assignedOrganization?->children->pluck('id')->toArray() ?? [],
                ...$grandIds
            ]);
        }

        return $ids;
    }
}
