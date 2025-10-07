<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginLog extends Model
{
    protected $fillable = [
        'user_id',
        'username',
        'event_type',
        'ip_address',
        'user_agent',
        'session_id',
        'success',
        'failure_reason',
        'additional_data'
    ];

    protected $casts = [
        'success' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log a login event
     */
    public static function logLogin(?int $userId, ?string $username, string $ipAddress, ?string $userAgent, bool $success = true, ?string $failureReason = null, ?array $additionalData = null)
    {
        return self::create([
            'user_id' => $userId,
            'username' => $username,
            'event_type' => $success ? 'login' : 'failed_login',
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'session_id' => session()->getId(),
            'success' => $success,
            'failure_reason' => $failureReason,
            'additional_data' => $additionalData ? json_encode($additionalData) : null
        ]);
    }

    /**
     * Log a logout event
     */
    public static function logLogout(int $userId, string $username, string $ipAddress, ?string $userAgent)
    {
        return self::create([
            'user_id' => $userId,
            'username' => $username,
            'event_type' => 'logout',
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'session_id' => session()->getId(),
            'success' => true
        ]);
    }
}
