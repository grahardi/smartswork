<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppNotification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'type',
        'message',
        'url',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper singkat untuk membuat notifikasi dari controller lain.
     */
    public static function kirim(int $userId, string $type, string $message, ?string $url = null): self
    {
        return static::create([
            'user_id' => $userId,
            'type' => $type,
            'message' => $message,
            'url' => $url,
        ]);
    }
}
