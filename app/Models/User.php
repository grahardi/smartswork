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

    public function residences(): HasMany
    {
        return $this->hasMany(Residence::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }

    public function sentFriendRequests(): HasMany
    {
        return $this->hasMany(Friendship::class, 'requester_id');
    }

    public function receivedFriendRequests(): HasMany
    {
        return $this->hasMany(Friendship::class, 'addressee_id');
    }

    /**
     * Daftar teman yang sudah saling terima (status accepted), dari kedua arah.
     */
    public function friends()
    {
        $sentIds = $this->sentFriendRequests()->where('status', 'accepted')->pluck('addressee_id');
        $receivedIds = $this->receivedFriendRequests()->where('status', 'accepted')->pluck('requester_id');

        return User::whereIn('id', $sentIds->merge($receivedIds))->get();
    }

    public function isFriendsWith(User $other): bool
    {
        return Friendship::where('status', 'accepted')
            ->where(function ($q) use ($other) {
                $q->where('requester_id', $this->id)->where('addressee_id', $other->id);
            })
            ->orWhere(function ($q) use ($other) {
                $q->where('requester_id', $other->id)->where('addressee_id', $this->id);
            })
            ->exists();
    }

    public function collaboratingProjects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_user')
            ->withPivot('role')
            ->withTimestamps();
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
