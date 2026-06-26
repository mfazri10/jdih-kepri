<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginLog extends Model
{
    protected $table = 'login_logs';

    public $timestamps = ["created_at"];
    const UPDATED_AT = null;

    // ─── FILLABLE ─────────────────────────────────────────────
    protected $fillable = [
        'user_id',
        'email',
        'ip_address',
        'user_agent',
        'status',
        'created_at',
    ];

    // ─── CASTS ────────────────────────────────────────────────
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    // ─── RELATIONSHIPS ────────────────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
