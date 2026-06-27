<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    protected $table = 'feedbacks';
    protected $fillable = [
        'user_id', 'name', 'email', 'type', 'subject',
        'message', 'rating', 'page_url', 'status',
        'admin_reply', 'replied_by', 'replied_at',
    ];

    protected function casts(): array
    {
        return [
            'user_id'   => 'integer',
            'rating'    => 'integer',
            'replied_by'=> 'integer',
            'replied_at'=> 'datetime',
        ];
    }

    public function scopeNew(Builder $q): Builder { return $q->where('status', 'new'); }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function replier(): BelongsTo { return $this->belongsTo(User::class, 'replied_by'); }
}
