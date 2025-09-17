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
        'aciklama'
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
}
