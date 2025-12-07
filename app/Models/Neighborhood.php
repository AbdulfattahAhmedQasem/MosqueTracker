<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Neighborhood extends Model
{
    /** @use HasFactory<\Database\Factories\NeighborhoodFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'province_id',
    ];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function mosques(): HasMany
    {
        return $this->hasMany(Mosque::class);
    }

    public function members(): HasManyThrough
    {
        return $this->hasManyThrough(Member::class, Mosque::class);
    }
}
