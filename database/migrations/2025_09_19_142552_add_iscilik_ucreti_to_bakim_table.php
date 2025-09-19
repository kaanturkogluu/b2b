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
            $table->decimal('iscilik_ucreti', 10, 2)->nullable()->after('ucret')->comment('İşçilik ücreti (opsiyonel)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bakim', function (Blueprint $table) {
            $table->dropColumn('iscilik_ucreti');
        });
    }
};
