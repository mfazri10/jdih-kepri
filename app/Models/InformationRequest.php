<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InformationRequest extends Model
{
    protected $fillable = [
        'register_number', 'user_id', 'name', 'email', 'phone',
        'institution', 'request_type', 'subject', 'description',
        'response', 'responded_by', 'responded_at', 'status',
        'due_date', 'attachment_url',
    ];

    protected function casts(): array
    {
        return [
            'user_id'      => 'integer',
            'responded_by' => 'integer',
            'due_date'     => 'date',
            'responded_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function responder(): BelongsTo { return $this->belongsTo(User::class, 'responded_by'); }
}
