<?php

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
      Schema::table('permissions', function (Blueprint $table) {
         $table->dropIndex('permissions_name_guard_name_unique');
         $table->unsignedBigInteger('parent_id')->nullable()
            ->after('guard_name');
         $table->index(['name', 'parent_id', 'guard_name'], 'idx_permissions_name_parent_id_guard_name_unique');
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::table('permissions', function (Blueprint $table) {
         $table->dropIndex('idx_permissions_name_parent_id_guard_name_unique');
         $table->dropColumn('parent_id');
         $table->index(['name', 'guard_name'], 'permissions_name_guard_name_unique');
      });
   }
};
