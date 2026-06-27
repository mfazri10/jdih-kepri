<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Subscription extends Model
{
    protected $fillable = [
        'user_id', 'email', 'category_id', 'type_id',
        'channel', 'is_active', 'token', 'last_notified_at',
    ];

    protected function casts(): array
    {
        return [
            'user_id'          => 'integer',
            'category_id'      => 'integer',
            'type_id'          => 'integer',
            'is_active'        => 'boolean',
            'last_notified_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($m) => $m->token = $m->token ?: Str::random(60));
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function type(): BelongsTo { return $this->belongsTo(DocumentType::class, 'type_id'); }
}
