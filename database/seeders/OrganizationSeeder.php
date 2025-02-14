<?php

namespace Database\Seeders;

use App\Models\OrganizationUnit;
use App\Models\User;
use App\Models\UsersOrganizations;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        OrganizationUnit::truncate();
        UsersOrganizations::truncate();

        $organization = OrganizationUnit::create([
            'name' => 'Cây tổ chức gốc',
            'description' => 'Cây tổ chức gốc',
            'phone_number' => $faker->phoneNumber,
            'address' => $faker->address,
            'tax_code' => $faker->numerify('##########'),
        ]);

        $adminUser = User::where('name', 'Admin')->firstOrFail();
        $regularUser = User::where('name', 'User')->firstOrFail();

        UsersOrganizations::insert([
            [
                'organization_id' => $organization->id,
                'user_id' => $adminUser->id,
            ],
            [
                'organization_id' => $organization->id,
                'user_id' => $regularUser->id,
            ],
        ]);
    }
}
