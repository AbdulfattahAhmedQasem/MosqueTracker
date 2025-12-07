<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Traits\HasOptimisticLocking;

class Housing extends Model
{
    /** @use HasFactory<\Database\Factories\HousingFactory> */
    use HasFactory;
    use HasOptimisticLocking;

    protected $fillable = [
        'name',
        'mosque_id',
    ];

    protected $visible = [
        'id',
        'name',
        'mosque_id',
        'created_at',
        'updated_at',
    ];

    public function mosque(): BelongsTo
    {
        return $this->belongsTo(Mosque::class);
    }

    public function member(): HasOne
    {
        return $this->hasOne(Member::class);
    }
}
