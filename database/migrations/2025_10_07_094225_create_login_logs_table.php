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
        Schema::create('login_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('username')->nullable(); // Başarısız girişler için
            $table->string('event_type'); // 'login', 'logout', 'failed_login'
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->string('session_id')->nullable();
            $table->boolean('success')->default(true);
            $table->string('failure_reason')->nullable();
            $table->text('additional_data')->nullable(); // JSON formatında ek bilgi
            $table->timestamps();
            
            // Foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            
            // İndeksler
            $table->index('user_id');
            $table->index('event_type');
            $table->index('ip_address');
            $table->index('created_at');
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_logs');
    }
};
