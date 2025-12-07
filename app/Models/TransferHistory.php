<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasOptimisticLocking;

class TransferHistory extends Model
{
    /** @use HasFactory<\Database\Factories\TransferHistoryFactory> */
    use HasFactory;
    use HasOptimisticLocking;

    protected $fillable = [
        'member_id',
        'from_mosque',
        'to_mosque',
        'transfer_date',
        'transferred_by',
        'reason',
        'old_category',
        'new_category',
    ];

    protected function casts(): array
    {
        return [
            'transfer_date' => 'date',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
