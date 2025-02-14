<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\OrganizationUnit;
use App\Models\Permission;
use App\Models\PermissionOrganization;
use App\Models\RoleOrganization;
use App\Models\User;
use App\Models\UserMenu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class AdminRoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now()->format('Y-m-d H:i:s');
        // Truncate roles and role-organization associations
        Role::truncate();
        RoleOrganization::truncate();
        UserMenu::truncate();

        // Get the first organization unit
        $organization = OrganizationUnit::firstOrFail();

        // Create roles
        $adminRole = Role::create(['name' => 'Admin']);
        $userRole = Role::create(['name' => 'User']);

        // Associate roles with the organization
        RoleOrganization::insert([
            ['role_id' => $adminRole->id, 'organization_id' => $organization->id],
            ['role_id' => $userRole->id, 'organization_id' => $organization->id],
        ]);

        // Get all permissions
        $allPermissions = Permission::pluck('name', 'id');

        // Assign roles to users
        $adminUser = User::where('name', 'Admin')->first();
        $regularUser = User::where('name', 'User')->first();

        if ($adminUser && $regularUser) {
            $adminUser->assignRole($adminRole);
            $regularUser->assignRole($userRole);

            // Sync all permissions to both roles
            $adminRole->syncPermissions($allPermissions);
            $userRole->syncPermissions($allPermissions);

            // Associate permissions with the organization
            $permissionOrganization = $allPermissions->map(function ($permission, $id) use ($organization) {
                return [
                    'permission_id' => $id,
                    'organization_id' => $organization->id,
                ];
            })->toArray();

            PermissionOrganization::insert($permissionOrganization);
            
            // Sync menu
            $adminMenuQuery = Menu::query()->select([
                DB::raw((int) $adminUser->id . ' as user_id'),
                "id as menu_id",
                DB::raw("'$now' as created_at"),
                DB::raw("'$now' as updated_at"),
            ]);
            
            $userMenuQuery = Menu::query()->select([
                DB::raw((int) $regularUser->id . ' as user_id'),
                "id as menu_id",
                DB::raw("'$now' as created_at"),
                DB::raw("'$now' as updated_at"),
            ]);
            UserMenu::query()->insertUsing(['user_id', 'menu_id', 'created_at', 'updated_at'], $adminMenuQuery);
            UserMenu::query()->insertUsing(['user_id', 'menu_id', 'created_at', 'updated_at'], $userMenuQuery);
        }
    }
}
