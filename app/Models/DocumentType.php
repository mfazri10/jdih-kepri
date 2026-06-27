<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentType extends Model
{
    // ─── FILLABLE ─────────────────────────────────────────────
    protected $fillable = [
        'name',
        'code',
        'description',
        'parent_id',
        'sort_order',
        'is_active',
    ];

    // ─── CASTS ────────────────────────────────────────────────
    protected function casts(): array
    {
        return [
            'is_active'  => 'boolean',
            'sort_order' => 'integer',
            'parent_id'  => 'integer',
        ];
    }

    // ─── LOCAL SCOPES ─────────────────────────────────────────
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // ─── RELATIONSHIPS ────────────────────────────────────────
    public function parent(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(DocumentType::class, 'parent_id')->orderBy('sort_order');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'type_id');
    }
}
