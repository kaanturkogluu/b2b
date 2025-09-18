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
        return self::create([
            'type' => $type,
            'description' => $description,
            'user_id' => $userId,
            'related_id' => $relatedId,
            'related_type' => $relatedType,
            'metadata' => $metadata
        ]);
    }
}
