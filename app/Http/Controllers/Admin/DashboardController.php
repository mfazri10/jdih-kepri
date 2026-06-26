<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:dashboard.view'),
        ];
    }

    public function index(): View
    {
        return view('admin.dashboard.index', [
            'stats' => [
                'roles' => Role::count(),
                'permissions' => Permission::count(),
                'users' => User::count(),
                'menus' => Menu::active()->count(),
            ],
            'roles' => Role::query()
                ->withCount('permissions')
                ->orderBy('name')
                ->get(),
            'recentUsers' => User::query()
                ->with('roles')
                ->latest()
                ->limit(5)
                ->get(),
            'menus' => Menu::query()
                ->ordered()
                ->limit(8)
                ->get(),
        ]);
    }
}
