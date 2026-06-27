<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
{
    // ─── FILLABLE ─────────────────────────────────────────────
    protected $fillable = [
        'document_id',
        'filename',
        'original_name',
        'file_url',
        'file_path',
        'file_size',
        'mime_type',
        'sort_order',
        'download_count',
        'created_by',
    ];

    // ─── CASTS ────────────────────────────────────────────────
    protected function casts(): array
    {
        return [
            'document_id'    => 'integer',
            'file_size'      => 'integer',
            'sort_order'     => 'integer',
            'download_count' => 'integer',
            'created_by'     => 'integer',
        ];
    }

    // ─── RELATIONSHIPS ────────────────────────────────────────
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ─── ACCESSORS ────────────────────────────────────────────
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
