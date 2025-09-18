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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'bakim_created', 'bakim_completed', 'user_created', etc.
            $table->string('description');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('related_id')->nullable(); // ID of related record
            $table->string('related_type')->nullable(); // Model type (Bakim, User, etc.)
            $table->json('metadata')->nullable(); // Additional data
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['type', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
