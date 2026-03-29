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
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->string('ip_address', 45)->nullable()->after('user_id');
            $table->text('user_agent')->nullable()->after('ip_address');
            $table->string('request_method', 10)->nullable()->after('user_agent');
            $table->text('request_url')->nullable()->after('request_method');
            $table->string('session_id')->nullable()->after('request_url');
            
            // İndeksler - performans için
            $table->index('ip_address');
            $table->index('session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex(['ip_address']);
            $table->dropIndex(['session_id']);
            $table->dropColumn(['ip_address', 'user_agent', 'request_method', 'request_url', 'session_id']);
        });
    }
};
