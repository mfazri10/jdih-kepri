<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentRelation extends Model
{
    // ─── FILLABLE ─────────────────────────────────────────────
    protected $fillable = [
        'source_id',
        'related_id',
        'relation_type',
    ];

    // ─── CASTS ────────────────────────────────────────────────
    protected function casts(): array
    {
        return [
            'source_id'  => 'integer',
            'related_id' => 'integer',
        ];
    }

    // ─── RELATIONSHIPS ────────────────────────────────────────
    public function source(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'source_id');
    }

    public function related(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'related_id');
    }
}
