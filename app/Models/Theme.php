<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Theme extends Model
{
    // ─── FILLABLE ─────────────────────────────────────────────
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'sort_order',
        'is_active',
        'documents_count',
    ];

    // ─── CASTS ────────────────────────────────────────────────
    protected function casts(): array
    {
        return [
            'is_active'       => 'boolean',
            'sort_order'      => 'integer',
            'documents_count' => 'integer',
        ];
    }

    // ─── BOOT ─────────────────────────────────────────────────
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('name') && !$model->isDirty('slug')) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    // ─── LOCAL SCOPES ─────────────────────────────────────────
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // ─── RELATIONSHIPS ────────────────────────────────────────
    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class, 'document_themes')->withTimestamps();
    }
}
