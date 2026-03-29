<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DegisecekParca extends Model
{
    protected $table = 'degisecek_parcalar';
    
    protected $fillable = [
        'bakim_id',
        'parca_adi',
        'adet',
        'birim_fiyat',
        'aciklama',
        'is_deleted',
        'deleted_at'
    ];

    protected $casts = [
        'adet' => 'integer',
        'birim_fiyat' => 'decimal:2'
    ];

    public function bakim(): BelongsTo
    {
        return $this->belongsTo(Bakim::class, 'bakim_id');
    }

    public function getToplamFiyatAttribute()
    {
        return $this->adet * $this->birim_fiyat;
    }

    /**
     * Scope: Sadece silinmemiş parçaları getir
     */
    public function scopeNotDeleted($query)
    {
        return $query->where('is_deleted', false);
    }

    /**
     * Scope: Sadece silinmiş parçaları getir
     */
    public function scopeOnlyDeleted($query)
    {
        return $query->where('is_deleted', true);
    }
}
