<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentVersion extends Model
{
    public $timestamps = false;

    // ─── FILLABLE ─────────────────────────────────────────────
    protected $fillable = [
        'document_id',
        'user_id',
        'old_values',
        'new_values',
        'created_at',
    ];

    // ─── CASTS ────────────────────────────────────────────────
    protected function casts(): array
    {
        return [
            'document_id' => 'integer',
            'user_id'     => 'integer',
            'old_values'  => 'array',
            'new_values'  => 'array',
            'created_at'  => 'datetime',
        ];
    }

    // ─── RELATIONSHIPS ────────────────────────────────────────
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
