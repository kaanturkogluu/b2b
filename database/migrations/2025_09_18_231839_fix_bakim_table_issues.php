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
            // personel_id'yi nullable yap
            $table->unsignedBigInteger('personel_id')->nullable()->change();
            
            // ucret alanını decimal olarak değiştir
            $table->decimal('ucret', 10, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bakim', function (Blueprint $table) {
            // Geri alma işlemleri
            $table->unsignedBigInteger('personel_id')->nullable(false)->change();
            $table->string('ucret')->default('0')->change();
        });
    }
};
