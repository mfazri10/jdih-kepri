<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HearingSubmission extends Model
{
    protected $fillable = [
        'hearing_id', 'user_id', 'name', 'email', 'institution',
        'opinion', 'attachment_url', 'status', 'admin_note',
        'reviewed_by', 'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'hearing_id'  => 'integer',
            'user_id'     => 'integer',
            'reviewed_by' => 'integer',
            'reviewed_at' => 'datetime',
        ];
    }

    public function hearing(): BelongsTo { return $this->belongsTo(PublicHearing::class, 'hearing_id'); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function reviewer(): BelongsTo { return $this->belongsTo(User::class, 'reviewed_by'); }
}
