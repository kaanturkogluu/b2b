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
            $table->boolean('is_deleted')->default(false)->after('tamamlanma_notu');
            $table->timestamp('deleted_at')->nullable()->after('is_deleted');
            $table->unsignedBigInteger('deleted_by')->nullable()->after('deleted_at');
            
            // Index for performance
            $table->index('is_deleted');
            
            // Foreign key for deleted_by
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('degisecek_parcalar', function (Blueprint $table) {
            $table->boolean('is_deleted')->default(false)->after('aciklama');
            $table->timestamp('deleted_at')->nullable()->after('is_deleted');
            
            // Index for performance
            $table->index('is_deleted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bakim', function (Blueprint $table) {
            $table->dropForeign(['deleted_by']);
            $table->dropIndex(['is_deleted']);
            $table->dropColumn(['is_deleted', 'deleted_at', 'deleted_by']);
        });

        Schema::table('degisecek_parcalar', function (Blueprint $table) {
            $table->dropIndex(['is_deleted']);
            $table->dropColumn(['is_deleted', 'deleted_at']);
        });
    }
};
