<?php

namespace Database\Seeders;

use App\Models\Configuration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Configuration::query()->truncate();
        Configuration::query()->insert([
            ['key' => 'app_name', 'value' => 'Quản Lý Hồ Sơ Công'],
            ['key' => 'language', 'value' => 'vn'],
            ['key' => 'date_format', 'value' => 'd/m/Y'],
            ['key' => 'time_format', 'value' => 'h:i:s A'],
            ['key' => 'favicon', 'value' => 'assets/img/favicon/favicon.ico'],
            ['key' => 'logo', 'value' => 'assets/img/logo/logo.png'],
            ['key' => 'email_protocol', 'value' => 'smtp'],
            ['key' => 'from_name', 'value' => 'Admin'],
            ['key' => 'from_address', 'value' => 'manager@nip.io.com'],
            ['key' => 'smtp_server', 'value' => 'mailcatcher'],
            ['key' => 'smtp_user', 'value' => null],
            ['key' => 'smtp_password', 'value' => null],
            ['key' => 'smtp_port', 'value' => '1025'],
            ['key' => 'security_type', 'value' => 'none'],
            ['key' => 'test_email', 'value' => 'test@email.com'],
        ]);
    }
}
