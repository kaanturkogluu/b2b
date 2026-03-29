<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bakim extends Model
{
    use HasFactory;
    
    protected $table = 'bakim';
    
    protected $fillable = [
        'plaka',
        'sase',
        'tahmini_teslim_tarihi',
        'telefon_numarasi',
        'musteri_adi',
        'odeme_durumu',
        'bakim_durumu',
        'ucret',
        'iscilik_ucreti',
        'genel_aciklama',
        'admin_id',
        'bakim_tarihi',
        'personel_id',
        'tamamlayan_personel_id',
        'tamamlanma_tarihi',
        'tamamlanma_notu',
        'is_deleted',
        'deleted_at',
        'deleted_by'
    ];

    protected $casts = [
        'tahmini_teslim_tarihi' => 'datetime',
        'bakim_tarihi' => 'datetime',
        'tamamlanma_tarihi' => 'datetime',
        'odeme_durumu' => 'integer',
        'ucret' => 'decimal:2',
        'iscilik_ucreti' => 'decimal:2'
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function personel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'personel_id');
    }

    public function tamamlayanPersonel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tamamlayan_personel_id');
    }

    public function degisecekParcalar(): HasMany
    {
        return $this->hasMany(DegisecekParca::class, 'bakim_id');
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Scope: Sadece silinmemiş kayıtları getir
     */
    public function scopeNotDeleted($query)
    {
        return $query->where('is_deleted', false);
    }

    /**
     * Scope: Sadece silinmiş kayıtları getir
     */
    public function scopeOnlyDeleted($query)
    {
        return $query->where('is_deleted', true);
    }

    /**
     * Soft delete işlemi
     */
    public function softDelete($userId = null)
    {
        $this->update([
            'is_deleted' => true,
            'deleted_at' => now(),
            'deleted_by' => $userId
        ]);

        // Parçaları da soft delete yap
        $this->degisecekParcalar()->update([
            'is_deleted' => true,
            'deleted_at' => now()
        ]);
    }

    /**
     * Soft delete'i geri al
     */
    public function restore()
    {
        $this->update([
            'is_deleted' => false,
            'deleted_at' => null,
            'deleted_by' => null
        ]);

        // Parçaları da geri yükle
        $this->degisecekParcalar()->update([
            'is_deleted' => false,
            'deleted_at' => null
        ]);
    }
}
