<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_demo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_demo' => 'boolean',
        ];
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function workplaces(): BelongsToMany
    {
        return $this->belongsToMany(Workplace::class, 'user_workplace')
            ->withPivot(['jabatan', 'tanggal_gabung'])
            ->withTimestamps();
    }

    public function dailyActions(): HasMany
    {
        return $this->hasMany(DailyAction::class);
    }

    public function financeCategories(): HasMany
    {
        return $this->hasMany(FinanceCategory::class);
    }

    public function financeTransactions(): HasMany
    {
        return $this->hasMany(FinanceTransaction::class);
    }

    /**
     * Dipanggil setelah registrasi (mis. dari RegisteredUserController)
     * untuk otomatis membuatkan workplace "Pribadi" + project default,
     * supaya user bisa langsung isi aksi harian tanpa setup manual.
     */
    public function provisionDefaultWorkplace(): Workplace
    {
        $workplace = Workplace::create([
            'nama' => 'Pribadi',
            'type' => 'personal',
            'is_default' => true,
        ]);

        $this->workplaces()->syncWithoutDetaching([
            $workplace->id => ['jabatan' => 'Pemilik', 'tanggal_gabung' => now()],
        ]);

        $workplace->projects()->create([
            'nama' => 'Kegiatan Harian',
            'status' => 'berjalan',
        ]);

        return $workplace;
    }
}
