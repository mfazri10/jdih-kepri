<?php

namespace App\Support;

use App\Models\Menu;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;

class AdminMenuBuilder
{
    public static function forUser(?Authenticatable $user): Collection
    {
        if (! $user) {
            return collect();
        }

        $menus = Menu::query()
            ->active()
            ->ordered()
            ->get()
            ->filter(fn (Menu $menu) => $menu->visibleFor($user))
            ->values();

        return self::buildTree($menus);
    }

    private static function buildTree(Collection $menus, ?int $parentId = null): Collection
    {
        return $menus
            ->where('menu_induk_id', $parentId)
            ->values()
            ->map(function (Menu $menu) use ($menus) {
                $children = self::buildTree($menus, $menu->id);

                $menu->setRelation('children', $children);

                return $menu;
            })
            ->filter(function (Menu $menu) {
                return $menu->resolvedUrl() !== 'javascript:void(0)' || $menu->children->isNotEmpty();
            })
            ->values();
    }
}
