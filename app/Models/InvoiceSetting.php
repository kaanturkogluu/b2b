<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceSetting extends Model
{
    protected $fillable = [
        'company_name',
        'address',
        'city',
        'phone',
        'email'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the first invoice setting or create default one
     */
    public static function getSettings()
    {
        $settings = self::first();
        
        if (!$settings) {
            $settings = self::create([
                'company_name' => 'MOTOJET SERVİS',
                'address' => 'Servis Mahallesi, Teknik Cad. No:123',
                'city' => 'İstanbul, Türkiye',
                'phone' => '+90 212 555 0123',
                'email' => 'info@motojetservis.com'
            ]);
        }
        
        return $settings;
    }
}
