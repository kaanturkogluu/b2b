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
        Schema::create('bakim', function (Blueprint $table) {
            $table->id();
            $table->string('plaka');
            $table->string('sase');
            $table->datetime('tahmini_teslim_tarihi');
            $table->string('telefon_numarasi');
            $table->string('musteri_adi');
            $table->integer('odeme_durumu')->default(0);
            $table->enum('bakim_durumu', ['Devam Ediyor', 'Tamamlandı'])->default('Devam Ediyor');
            $table->string('ucret')->default('0');
            $table->string('genel_aciklama')->default('Beklemede');
            $table->unsignedBigInteger('admin_id');
            $table->datetime('bakim_tarihi');
            $table->unsignedBigInteger('personel_id');
            $table->timestamps();
            
            $table->foreign('admin_id')->references('id')->on('users');
            $table->foreign('personel_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bakim');
    }
};
