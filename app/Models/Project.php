<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'workplace_id',
        'nama',
        'planning',
        'target',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function workplace(): BelongsTo
    {
        return $this->belongsTo(Workplace::class);
    }

    public function dailyActions(): HasMany
    {
        return $this->hasMany(DailyAction::class);
    }
}
