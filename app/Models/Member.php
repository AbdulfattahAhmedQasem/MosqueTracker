<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasOptimisticLocking;

class Member extends Model
{
    /** @use HasFactory<\Database\Factories\MemberFactory> */
    use HasFactory;
    use HasOptimisticLocking;

    protected $fillable = [
        'name',
        'mosque_id',
        'housing_id',
        'category_id',
        'profession_id',
        'employee_number',
        'phone',
        'national_id',
        'appointment_decision',
        'appointment_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'appointment_date' => 'date',
        ];
    }

    public function mosque(): BelongsTo
    {
        return $this->belongsTo(Mosque::class);
    }

    public function housing(): BelongsTo
    {
        return $this->belongsTo(Housing::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function transferHistories(): HasMany
    {
        return $this->hasMany(TransferHistory::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function profession(): BelongsTo
    {
        return $this->belongsTo(Profession::class, 'profession_id');
    }

    /**
     * @deprecated Use category() instead
     */
    public function categoryModel(): BelongsTo
    {
        return $this->category();
    }

    /**
     * @deprecated Use profession() instead
     */
    public function professionModel(): BelongsTo
    {
        return $this->profession();
    }
}

