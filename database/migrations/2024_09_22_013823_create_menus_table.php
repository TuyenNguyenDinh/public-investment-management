<?php

use App\Enums\Menus\MenuAllowDeleteEnum;
use App\Enums\Menus\MenuStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Kalnoy\Nestedset\NestedSet;

return new class extends Migration {
   /**
    * Run the migrations.
    */
   public function up(): void
   {
      Schema::create('menus', function (Blueprint $table) {
         $table->id();
         $table->string('name');
         $table->string('icon')->nullable();
         $table->string('slug')->nullable();
         $table->string('route_name')->nullable();
         $table->string('url')->nullable();
         $table->tinyInteger('status')->default(MenuStatusEnum::ACTIVE)
            ->comment('0: Inactive, 1: Active');
         $table->tinyInteger('allow_delete')->default(MenuAllowDeleteEnum::ALLOW)
            ->comment('0: Deny, 1: Allow');
         NestedSet::columns($table);
         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('menus');
   }
};
