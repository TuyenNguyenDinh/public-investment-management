<?php

namespace App\Rules;

use App\Models\OrganizationUnit;
use App\Models\Role;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckRoleHasOrganizationRule implements ValidationRule
{
    protected array $roleNames;

    public function __construct(array $roleNames)
    {
        $this->roleNames = $roleNames;
    }

    /**
     * Run the validation rule.
     *
     * @param \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $roleNames = Role::query()->whereIn('name', $this->roleNames)->pluck('name')->toArray();
        $roleNamesSet = array_flip($roleNames);

        $organizations = OrganizationUnit::query()
            ->with('roles:name')
            ->whereIn('id', $value)
            ->get();

        foreach ($organizations as $organization) {
            $organizationName = $organization->name;
            $organizationRoles = $organization->roles()->pluck('name')->toArray();
            $organizationRolesSet = array_flip($organizationRoles);

            foreach ($roleNamesSet as $roleName => $_) {
                if (!isset($organizationRolesSet[$roleName])) {
                    $fail(__('organization_not_in_role', [
                        'role_name' => $roleName,
                        'organization_name' => $organizationName
                    ]));
                    break 2;
                }
            }
        }
    }
}
