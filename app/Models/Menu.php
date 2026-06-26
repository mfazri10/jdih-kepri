<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Route;

class Menu extends Model
{
    protected $fillable = [
        'nama_menu',
        'nama_fitur',
        'alamat_url',
        'route_name',
        'ikon',
        'tingkatan_menu',
        'urutan',
        'menu_induk_id',
        'permission_name',
        'tag',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'menu_induk_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'menu_induk_id')->orderBy('urutan')->orderBy('nama_menu');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('urutan')->orderBy('nama_menu');
    }

    public function visibleFor(?Authenticatable $user): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if (blank($this->permission_name)) {
            return true;
        }

        return $user?->can($this->permission_name) ?? false;
    }

    public function resolvedUrl(): string
    {
        if (filled($this->route_name) && Route::has($this->route_name)) {
            return route($this->route_name);
        }

        return filled($this->alamat_url) ? $this->alamat_url : 'javascript:void(0)';
    }

    public function hasVisibleChildren(Collection $children): bool
    {
        return $children->isNotEmpty();
    }
}
