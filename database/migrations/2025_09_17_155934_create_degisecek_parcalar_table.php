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
        Schema::create('degisecek_parcalar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bakim_id');
            $table->string('parca_adi');
            $table->integer('adet');
            $table->decimal('birim_fiyat', 10, 2);
            $table->text('aciklama')->nullable();
            $table->timestamps();
            
            $table->foreign('bakim_id')->references('id')->on('bakim')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('degisecek_parcalar');
    }
};
