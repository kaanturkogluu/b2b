<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'type',
        'description',
        'user_id',
        'ip_address',
        'user_agent',
        'request_method',
        'request_url',
        'session_id',
        'related_id',
        'related_type',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function related()
    {
        return $this->morphTo('related', 'related_type', 'related_id');
    }

    public static function log(string $type, string $description, ?int $userId = null, ?int $relatedId = null, ?string $relatedType = null, ?array $metadata = null)
    {
        $request = request();
        
        return self::create([
            'type' => $type,
            'description' => $description,
            'user_id' => $userId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'request_method' => $request->method(),
            'request_url' => $request->fullUrl(),
            'session_id' => session()->getId(),
            'related_id' => $relatedId,
            'related_type' => $relatedType,
            'metadata' => $metadata
        ]);
    }
}
