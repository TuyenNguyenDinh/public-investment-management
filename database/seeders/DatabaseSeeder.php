<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        $this->call(AdminDataSeeder::class);
        $this->call(OrganizationSeeder::class);
        $this->call(MenuTableSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(AdminRoleAndPermissionSeeder::class);
        $this->call(AddCategorySeeder::class);
        $this->call(AddPostSeeder::class);
    }
}
