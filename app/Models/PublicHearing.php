<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PublicHearing extends Model
{
    protected $fillable = [
        'title', 'description', 'document_draft', 'start_date', 'end_date',
        'status', 'location', 'online_link', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date'   => 'date',
            'created_by' => 'integer',
        ];
    }

    public function scopeOpen(Builder $q): Builder { return $q->where('status', 'open'); }

    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function submissions(): HasMany { return $this->hasMany(HearingSubmission::class, 'hearing_id'); }
}
