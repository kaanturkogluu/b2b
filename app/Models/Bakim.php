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
        'genel_aciklama',
        'admin_id',
        'bakim_tarihi',
        'personel_id',
        'tamamlayan_personel_id',
        'tamamlanma_tarihi',
        'tamamlanma_notu'
    ];

    protected $casts = [
        'tahmini_teslim_tarihi' => 'datetime',
        'bakim_tarihi' => 'datetime',
        'tamamlanma_tarihi' => 'datetime',
        'odeme_durumu' => 'integer',
        'ucret' => 'decimal:2'
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
}
