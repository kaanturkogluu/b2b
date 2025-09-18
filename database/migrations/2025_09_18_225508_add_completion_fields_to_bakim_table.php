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
            $table->unsignedBigInteger('tamamlayan_personel_id')->nullable()->after('personel_id');
            $table->timestamp('tamamlanma_tarihi')->nullable()->after('tamamlayan_personel_id');
            $table->text('tamamlanma_notu')->nullable()->after('tamamlanma_tarihi');
            
            $table->foreign('tamamlayan_personel_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bakim', function (Blueprint $table) {
            $table->dropForeign(['tamamlayan_personel_id']);
            $table->dropColumn(['tamamlayan_personel_id', 'tamamlanma_tarihi', 'tamamlanma_notu']);
        });
    }
};
