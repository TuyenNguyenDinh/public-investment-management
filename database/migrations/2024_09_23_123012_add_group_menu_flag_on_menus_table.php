<?php

use App\Enums\Menus\MenuGroupFlagEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
   /**
    * Run the migrations.
    */
   public function up(): void
   {
      Schema::table('menus', function (Blueprint $table) {
         $table->tinyInteger('group_menu_flag')->default(MenuGroupFlagEnum::INACTIVE)->after('allow_delete');
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::table('menus', function (Blueprint $table) {
         $table->dropColumn('group_menu_flag');
      });
   }
};
