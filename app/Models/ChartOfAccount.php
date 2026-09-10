<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChartOfAccount extends Model
{
    protected $fillable = [
        'business_id',
        'kode',
        'nama',
        'tipe',
        'saldo_normal',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function lines(): HasMany
    {
        return $this->hasMany(JournalEntryLine::class, 'chart_of_account_id');
    }

    /**
     * Saldo akun ini sampai tanggal tertentu (default: semua). Menghormati
     * saldo_normal - kalau normalnya debit, saldo = total debit - total kredit,
     * dan sebaliknya untuk akun kredit-normal.
     */
    public function saldo(?string $sampaiTanggal = null): float
    {
        $query = $this->lines()->whereHas('journalEntry', function ($q) use ($sampaiTanggal) {
            if ($sampaiTanggal) {
                $q->where('tanggal', '<=', $sampaiTanggal);
            }
        });

        $totalDebit = (float) $query->clone()->sum('debit');
        $totalKredit = (float) $query->clone()->sum('kredit');

        return $this->saldo_normal === 'debit'
            ? $totalDebit - $totalKredit
            : $totalKredit - $totalDebit;
    }
}
