<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SearchQueryLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'query',
        'results_count',
        'user_id',
        'ip_address',
        'filters',
        'searched_at',
    ];

    protected function casts(): array
    {
        return [
            'results_count' => 'integer',
            'user_id'       => 'integer',
            'filters'       => 'array',
            'searched_at'   => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
