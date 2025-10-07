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
        Schema::table('bakim', function (Blueprint $table) {
            // Drop existing foreign keys
            $table->dropForeign(['admin_id']);
            $table->dropForeign(['personel_id']);
            
            // Make columns nullable (required for set null constraint)
            $table->unsignedBigInteger('admin_id')->nullable()->change();
            $table->unsignedBigInteger('personel_id')->nullable()->change();
            
            // Recreate with onDelete set null constraint
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('personel_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bakim', function (Blueprint $table) {
            // Drop the new foreign keys
            $table->dropForeign(['admin_id']);
            $table->dropForeign(['personel_id']);
            
            // Make columns non-nullable again
            $table->unsignedBigInteger('admin_id')->nullable(false)->change();
            $table->unsignedBigInteger('personel_id')->nullable(false)->change();
            
            // Recreate original foreign keys without onDelete
            $table->foreign('admin_id')->references('id')->on('users');
            $table->foreign('personel_id')->references('id')->on('users');
        });
    }
};
