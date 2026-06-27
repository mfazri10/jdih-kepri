<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Document extends Model
{
    use SoftDeletes;

    // ─── FILLABLE ─────────────────────────────────────────────
    protected $fillable = [
        'type_id',
        'category_id',
        'title',
        'number',
        'year',
        'slug',
        'status',
        'teu',
        'subject',
        'source',
        'signatory',
        'place',
        'date_set',
        'date_publish',
        'date_effective',
        'abstract',
        'full_text',
        'language',
        'views_count',
        'downloads_count',
        'is_featured',
        'published_at',
        'created_by',
        'updated_by',
    ];

    // ─── CASTS ────────────────────────────────────────────────
    protected function casts(): array
    {
        return [
            'type_id'         => 'integer',
            'category_id'     => 'integer',
            'year'            => 'integer',
            'views_count'     => 'integer',
            'downloads_count' => 'integer',
            'is_featured'     => 'boolean',
            'date_set'        => 'date',
            'date_publish'    => 'date',
            'date_effective'  => 'date',
            'published_at'    => 'datetime',
            'created_by'      => 'integer',
            'updated_by'      => 'integer',
        ];
    }

    // ─── BOOT ─────────────────────────────────────────────────
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title . ' ' . $model->number . ' ' . $model->year);
            }
            if (empty($model->created_by) && auth()->check()) {
                $model->created_by = auth()->id();
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('title') && !$model->isDirty('slug')) {
                $model->slug = Str::slug($model->title . ' ' . $model->number . ' ' . $model->year);
            }
            if (auth()->check()) {
                $model->updated_by = auth()->id();
            }
        });
    }

    // ─── LOCAL SCOPES ─────────────────────────────────────────
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'berlaku')
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'berlaku');
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeByType(Builder $query, int $typeId): Builder
    {
        return $query->where('type_id', $typeId);
    }

    public function scopeByCategory(Builder $query, int $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeByYear(Builder $query, int $year): Builder
    {
        return $query->where('year', $year);
    }

    // ─── RELATIONSHIPS ────────────────────────────────────────
    public function type(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class, 'type_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function themes(): BelongsToMany
    {
        return $this->belongsToMany(Theme::class, 'document_themes')->withTimestamps();
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'document_tags')->withTimestamps();
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class)->orderBy('sort_order');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(DocumentVersion::class)->orderBy('created_at', 'desc');
    }

    public function sourceRelations(): HasMany
    {
        return $this->hasMany(DocumentRelation::class, 'source_id');
    }

    public function relatedRelations(): HasMany
    {
        return $this->hasMany(DocumentRelation::class, 'related_id');
    }
}
