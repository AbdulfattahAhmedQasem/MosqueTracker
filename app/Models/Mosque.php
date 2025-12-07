<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mosque extends Model
{
    /** @use HasFactory<\Database\Factories\MosqueFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'neighborhood_id',
    ];

    public function neighborhood(): BelongsTo
    {
        return $this->belongsTo(Neighborhood::class);
    }

    public function housings(): HasMany
    {
        return $this->hasMany(Housing::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }
}
