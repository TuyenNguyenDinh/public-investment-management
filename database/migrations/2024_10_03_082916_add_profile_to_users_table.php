<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('profile_photo_path');
            $table->date('date_of_birth')->nullable()->after('avatar');
            $table->string('citizen_identification')->nullable()->after('date_of_birth');
            $table->string('front_citizen_identification_img')->nullable()->after('citizen_identification');
            $table->string('back_citizen_identification_img')->nullable()->after('front_citizen_identification_img');
            $table->string('phone_number')->nullable()->after('back_citizen_identification_img');
            $table->string('hometown')->nullable()->after('phone_number');
            $table->string('permanent_address')->nullable()->after('hometown');
            $table->string('temporary_address')->nullable()->after('permanent_address');
            $table->string('education_level')->nullable()->after('temporary_address');
            $table->string('health_status')->nullable()->after('education_level');
            $table->integer('height')->nullable()->after('health_status');
            $table->integer('weight')->nullable()->after('height');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
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
                'weight'
            ]);
        });
    }
};
