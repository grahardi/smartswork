<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class DailyAction extends Model
{
    protected $fillable = [
        'user_id',
        'project_id',
        'tanggal',
        'waktu',
        'foto',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    // Akses cepat ke workplace tanpa perlu simpan workplace_id terpisah
    public function workplace()
    {
        return $this->project->workplace;
    }
}
