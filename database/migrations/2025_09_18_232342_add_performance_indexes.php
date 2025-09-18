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
        // Bakım tablosu indeksleri - sadece yoksa ekle
        Schema::table('bakim', function (Blueprint $table) {
            // Arama için indeksler
            if (!Schema::hasIndex('bakim', 'bakim_plaka_index')) {
                $table->index('plaka');
            }
            if (!Schema::hasIndex('bakim', 'bakim_musteri_adi_index')) {
                $table->index('musteri_adi');
            }
            if (!Schema::hasIndex('bakim', 'bakim_telefon_numarasi_index')) {
                $table->index('telefon_numarasi');
            }
            if (!Schema::hasIndex('bakim', 'bakim_sase_index')) {
                $table->index('sase');
            }
            
            // Filtreleme için indeksler
            if (!Schema::hasIndex('bakim', 'bakim_bakim_durumu_index')) {
                $table->index('bakim_durumu');
            }
            if (!Schema::hasIndex('bakim', 'bakim_odeme_durumu_index')) {
                $table->index('odeme_durumu');
            }
            if (!Schema::hasIndex('bakim', 'bakim_personel_id_index')) {
                $table->index('personel_id');
            }
            if (!Schema::hasIndex('bakim', 'bakim_admin_id_index')) {
                $table->index('admin_id');
            }
            
            // Tarih indeksleri
            if (!Schema::hasIndex('bakim', 'bakim_bakim_tarihi_index')) {
                $table->index('bakim_tarihi');
            }
            if (!Schema::hasIndex('bakim', 'bakim_tahmini_teslim_tarihi_index')) {
                $table->index('tahmini_teslim_tarihi');
            }
            if (!Schema::hasIndex('bakim', 'bakim_created_at_index')) {
                $table->index('created_at');
            }
            
            // Ücret indeksi
            if (!Schema::hasIndex('bakim', 'bakim_ucret_index')) {
                $table->index('ucret');
            }
            
            // Composite indeksler (çoklu filtreleme için)
            if (!Schema::hasIndex('bakim', 'bakim_bakim_durumu_odeme_durumu_index')) {
                $table->index(['bakim_durumu', 'odeme_durumu']);
            }
            if (!Schema::hasIndex('bakim', 'bakim_personel_id_bakim_durumu_index')) {
                $table->index(['personel_id', 'bakim_durumu']);
            }
            if (!Schema::hasIndex('bakim', 'bakim_bakim_tarihi_bakim_durumu_index')) {
                $table->index(['bakim_tarihi', 'bakim_durumu']);
            }
        });
        
        // Users tablosu indeksleri
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasIndex('users', 'users_role_index')) {
                $table->index('role');
            }
            if (!Schema::hasIndex('users', 'users_is_active_index')) {
                $table->index('is_active');
            }
            if (!Schema::hasIndex('users', 'users_role_is_active_index')) {
                $table->index(['role', 'is_active']);
            }
        });
        
        // Değişecek parçalar tablosu indeksleri
        Schema::table('degisecek_parcalar', function (Blueprint $table) {
            if (!Schema::hasIndex('degisecek_parcalar', 'degisecek_parcalar_bakim_id_index')) {
                $table->index('bakim_id');
            }
        });
        
        // Activity logs tablosu indeksleri (zaten var olan indeksleri kontrol et)
        Schema::table('activity_logs', function (Blueprint $table) {
            // Sadece yoksa ekle
            if (!Schema::hasIndex('activity_logs', 'activity_logs_user_id_created_at_index')) {
                $table->index(['user_id', 'created_at']);
            }
            if (!Schema::hasIndex('activity_logs', 'activity_logs_related_type_index')) {
                $table->index('related_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Bakım tablosu indekslerini kaldır
        Schema::table('bakim', function (Blueprint $table) {
            $table->dropIndex(['plaka']);
            $table->dropIndex(['musteri_adi']);
            $table->dropIndex(['telefon_numarasi']);
            $table->dropIndex(['sase']);
            $table->dropIndex(['bakim_durumu']);
            $table->dropIndex(['odeme_durumu']);
            $table->dropIndex(['personel_id']);
            $table->dropIndex(['admin_id']);
            $table->dropIndex(['bakim_tarihi']);
            $table->dropIndex(['tahmini_teslim_tarihi']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['ucret']);
            $table->dropIndex(['bakim_durumu', 'odeme_durumu']);
            $table->dropIndex(['personel_id', 'bakim_durumu']);
            $table->dropIndex(['bakim_tarihi', 'bakim_durumu']);
        });
        
        // Users tablosu indekslerini kaldır
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['role', 'is_active']);
        });
        
        // Değişecek parçalar tablosu indekslerini kaldır
        Schema::table('degisecek_parcalar', function (Blueprint $table) {
            $table->dropIndex(['bakim_id']);
        });
        
        // Activity logs tablosu indekslerini kaldır
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex(['type', 'created_at']);
            $table->dropIndex(['user_id', 'created_at']);
            $table->dropIndex(['related_type']);
        });
    }
};
