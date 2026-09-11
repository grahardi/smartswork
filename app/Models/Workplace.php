<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workplace extends Model
{
    protected $fillable = [
        'business_id',
        'nama',
        'alamat',
        'latitude',
        'longitude',
        'keterangan',
        'type',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function hasCoordinates(): bool
    {
        return ! is_null($this->latitude) && ! is_null($this->longitude);
    }

    public function business(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Jarak lurus (km) ke titik lain via rumus Haversine.
     */
    public function distanceToKm(float $lat, float $lng): ?float
    {
        if (! $this->hasCoordinates()) {
            return null;
        }

        $earthRadiusKm = 6371;

        $latFrom = deg2rad((float) $this->latitude);
        $lngFrom = deg2rad((float) $this->longitude);
        $latTo = deg2rad($lat);
        $lngTo = deg2rad($lng);

        $latDelta = $latTo - $latFrom;
        $lngDelta = $lngTo - $lngFrom;

        $a = sin($latDelta / 2) ** 2 + cos($latFrom) * cos($latTo) * sin($lngDelta / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadiusKm * $c, 2);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_workplace')
            ->withPivot(['jabatan', 'tanggal_gabung'])
            ->withTimestamps();
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
