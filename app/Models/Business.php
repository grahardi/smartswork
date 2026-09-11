<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    protected $fillable = [
        'owner_id',
        'nama_usaha',
        'npwp',
        'jenis_usaha',
        'alamat',
        'mata_uang',
        'skema_pajak',
        'pajak_custom_persen',
        'pajak_custom_basis',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'business_user')->withPivot('role')->withTimestamps();
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(ChartOfAccount::class);
    }

    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function workplace(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Workplace::class);
    }
}
