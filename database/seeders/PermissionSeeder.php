<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::query()->truncate();
        Permission::query()->insert([
            [
                "id" => 1,
                "name" => "Users Management",
                "guard_name" => "web",
                "parent_id" => null,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 2,
                "name" => "Menus Management",
                "guard_name" => "web",
                "parent_id" => null,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 3,
                "name" => "Roles Management",
                "guard_name" => "web",
                "parent_id" => null,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 4,
                "name" => "Organizations Management",
                "guard_name" => "web",
                "parent_id" => null,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 6,
                "name" => "Access Users",
                "guard_name" => "web",
                "parent_id" => 1,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 7,
                "name" => "Create Users",
                "guard_name" => "web",
                "parent_id" => 1,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 8,
                "name" => "Update Users",
                "guard_name" => "web",
                "parent_id" => 1,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 9,
                "name" => "Delete Users",
                "guard_name" => "web",
                "parent_id" => 1,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 10,
                "name" => "Access Menus",
                "guard_name" => "web",
                "parent_id" => 2,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 11,
                "name" => "Create Menus",
                "guard_name" => "web",
                "parent_id" => 2,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 12,
                "name" => "Update Menus",
                "guard_name" => "web",
                "parent_id" => 2,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 13,
                "name" => "Delete Menus",
                "guard_name" => "web",
                "parent_id" => 2,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 14,
                "name" => "Access Roles",
                "guard_name" => "web",
                "parent_id" => 3,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 15,
                "name" => "Create Roles",
                "guard_name" => "web",
                "parent_id" => 3,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 16,
                "name" => "Update Roles",
                "guard_name" => "web",
                "parent_id" => 3,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 17,
                "name" => "Delete Roles",
                "guard_name" => "web",
                "parent_id" => 3,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 18,
                "name" => "Access Organizations",
                "guard_name" => "web",
                "parent_id" => 4,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 19,
                "name" => "Create Organizations",
                "guard_name" => "web",
                "parent_id" => 4,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 20,
                "name" => "Update Organizations",
                "guard_name" => "web",
                "parent_id" => 4,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 21,
                "name" => "Delete Organizations",
                "guard_name" => "web",
                "parent_id" => 4,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 25,
                "name" => "Permissions Management",
                "guard_name" => "web",
                "parent_id" => null,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 26,
                "name" => "Access Permissions",
                "guard_name" => "web",
                "parent_id" => 25,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 27,
                "name" => "Create Permissions",
                "guard_name" => "web",
                "parent_id" => 25,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 28,
                "name" => "Update Permissions",
                "guard_name" => "web",
                "parent_id" => 25,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 29,
                "name" => "Delete Permissions",
                "guard_name" => "web",
                "parent_id" => 25,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 30,
                "name" => "Dashboards Management",
                "guard_name" => "web",
                "parent_id" => null,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 32,
                "name" => "Access Dashboards",
                "guard_name" => "web",
                "parent_id" => 30,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 36,
                "name" => "Posts Management",
                "guard_name" => "web",
                "parent_id" => null,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 37,
                "name" => "Access Posts",
                "guard_name" => "web",
                "parent_id" => 36,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 38,
                "name" => "Create Posts",
                "guard_name" => "web",
                "parent_id" => 36,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 39,
                "name" => "Update Posts",
                "guard_name" => "web",
                "parent_id" => 36,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 40,
                "name" => "Delete Posts",
                "guard_name" => "web",
                "parent_id" => 36,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 41,
                "name" => "Configs Management",
                "guard_name" => "web",
                "parent_id" => null,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 42,
                "name" => "Access Configs",
                "guard_name" => "web",
                "parent_id" => 41,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 43,
                "name" => "Update Configs",
                "guard_name" => "web",
                "parent_id" => 41,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 44,
                "name" => "Categories Management",
                "guard_name" => "web",
                "parent_id" => null,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 49,
                "name" => "Access Categories",
                "guard_name" => "web",
                "parent_id" => 44,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 50,
                "name" => "Create Categories",
                "guard_name" => "web",
                "parent_id" => 44,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 51,
                "name" => "Update Categories",
                "guard_name" => "web",
                "parent_id" => 44,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => 52,
                "name" => "Delete Categories",
                "guard_name" => "web",
                "parent_id" => 44,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id"=> 53,
                "name"=> "Logs Management",
                "guard_name"=> "web",
                "parent_id"=> null,
                "created_at"=> now(),
                "updated_at"=> now()
            ],
            [
                "id"=> 54,
                "name"=> "Access Logs",
                "guard_name"=> "web",
                "parent_id"=> 53,
                "created_at"=> now(),
                "updated_at"=> now()
            ],
            [
                "id"=> 55,
                "name"=> "Import Posts",
                "guard_name"=> "web",
                "parent_id"=> 36,
                "created_at"=> now(),
                "updated_at"=> now()
            ],
            [
                "id"=> 56,
                "name"=> "Export Posts",
                "guard_name"=> "web",
                "parent_id"=> 36,
                "created_at"=> now(),
                "updated_at"=> now()
            ],
            [
                "id"=> 57,
                "name"=> "Review Posts",
                "guard_name"=> "web",
                "parent_id"=> 36,
                "created_at"=> now(),
                "updated_at"=> now()
            ]
        ]);
    }
}
