<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BankAccount extends Model
{
    protected $fillable = [
        'user_id',
        'nama_bank',
        'no_rekening',
        'saldo_awal',
    ];

    protected $casts = [
        'saldo_awal' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function financeTransactions(): HasMany
    {
        return $this->hasMany(FinanceTransaction::class);
    }

    /**
     * Saldo awal + semua pemasukan yang ditandai ke rekening ini - semua
     * pengeluaran yang ditandai ke rekening ini.
     */
    public function saldoSekarang(): float
    {
        $masuk = (float) $this->financeTransactions()
            ->whereHas('category', fn ($q) => $q->where('type', 'pemasukan'))
            ->sum('jumlah');

        $keluar = (float) $this->financeTransactions()
            ->whereHas('category', fn ($q) => $q->where('type', 'pengeluaran'))
            ->sum('jumlah');

        return (float) $this->saldo_awal + $masuk - $keluar;
    }
}
