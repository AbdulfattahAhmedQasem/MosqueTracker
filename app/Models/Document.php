<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasOptimisticLocking;

class Document extends Model
{
    /** @use HasFactory<\Database\Factories\DocumentFactory> */
    use HasFactory;
    use HasOptimisticLocking;

    protected $fillable = [
        'member_id',
        'document_name',
        'document_type',
        'upload_date',
        'notes',
        'file_name',
        'file_size',
        'file_type',
        'file_path',
    ];

    protected function casts(): array
    {
        return [
            'upload_date' => 'date',
            'file_size' => 'integer',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
