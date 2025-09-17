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
        Schema::create('invoice_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->default('MOTOJET SERVİS');
            $table->text('address')->default('Servis Mahallesi, Teknik Cad. No:123');
            $table->string('city')->default('İstanbul, Türkiye');
            $table->string('phone')->default('+90 212 555 0123');
            $table->string('email')->default('info@motojetservis.com');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_settings');
    }
};
