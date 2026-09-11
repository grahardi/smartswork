<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BankAccount extends Model
{
    protected $fillable = [
        'user_id',
        'jenis',
        'provider',
        'nama_bank',
        'no_rekening',
        'saldo_awal',
    ];

    protected $casts = [
        'saldo_awal' => 'decimal:2',
    ];

    /**
     * Warna & label badge untuk e-wallet yang dikenal. Pakai badge warna
     * polos (bukan logo asli) biar aman dari hak cipta merek.
     */
    public static function providerBadge(?string $provider): array
    {
        return match ($provider) {
            'ovo' => ['warna' => '#4C2A86', 'label' => 'OVO'],
            'gopay' => ['warna' => '#00AED6', 'label' => 'GoPay'],
            'dana' => ['warna' => '#118EEA', 'label' => 'DANA'],
            'shopeepay' => ['warna' => '#EE4D2D', 'label' => 'ShopeePay'],
            'linkaja' => ['warna' => '#E9252B', 'label' => 'LinkAja'],
            default => ['warna' => '#667085', 'label' => 'E-Wallet'],
        };
    }

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
