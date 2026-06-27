<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consultation extends Model
{
    protected $fillable = [
        'user_id', 'name', 'email', 'phone', 'subject',
        'question', 'answer', 'answered_by', 'answered_at',
        'status', 'is_public',
    ];

    protected function casts(): array
    {
        return [
            'user_id'     => 'integer',
            'answered_by' => 'integer',
            'is_public'   => 'boolean',
            'answered_at' => 'datetime',
        ];
    }

    public function scopePending(Builder $q): Builder { return $q->where('status', 'pending'); }
    public function scopeAnswered(Builder $q): Builder { return $q->where('status', 'answered'); }
    public function scopePublic(Builder $q): Builder { return $q->where('is_public', true)->where('status', 'answered'); }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function answerer(): BelongsTo { return $this->belongsTo(User::class, 'answered_by'); }
}
