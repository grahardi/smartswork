<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FriendPermission extends Model
{
    protected $fillable = [
        'user_id',
        'friend_user_id',
        'can_view_aksi_harian',
        'can_view_keuangan',
        'can_view_tempat_kerja',
    ];

    protected $casts = [
        'can_view_aksi_harian' => 'boolean',
        'can_view_keuangan' => 'boolean',
        'can_view_tempat_kerja' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function friend(): BelongsTo
    {
        return $this->belongsTo(User::class, 'friend_user_id');
    }
}
