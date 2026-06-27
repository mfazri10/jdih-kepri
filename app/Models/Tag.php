<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Tag extends Model
{
    // ─── FILLABLE ─────────────────────────────────────────────
    protected $fillable = [
        'name',
        'slug',
    ];

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

    // ─── RELATIONSHIPS ────────────────────────────────────────
    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class, 'document_tags')->withTimestamps();
    }
}
