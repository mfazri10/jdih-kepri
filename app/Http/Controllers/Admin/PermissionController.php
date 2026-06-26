<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:permissions.view', only: ['index']),
            new Middleware('permission:permissions.create', only: ['create', 'store']),
            new Middleware('permission:permissions.update', only: ['edit', 'update']),
            new Middleware('permission:permissions.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));
        $guard = $this->guardName();

        $permissions = Permission::query()
            ->where('guard_name', $guard)
            ->when($search !== '', fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.permissions.index', [
            'permissions' => $permissions,
            'search' => $search,
            'modules' => $this->modules(),
        ]);
    }

    public function create(): View
    {
        return view('admin.permissions.create', [
            'permission' => new Permission(['guard_name' => $this->guardName()]),
            'modules' => $this->modules(),
            'selectedModule' => null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $guard = $this->guardName();
        $data = $this->validatePermission($request, $guard);

        Permission::create([
            'name' => $data['name'],
            'guard_name' => $guard,
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('admin.permissions.index')->with('success', 'Permission berhasil ditambahkan.');
    }

    public function edit(Permission $permission): View
    {
        abort_unless($permission->guard_name === $this->guardName(), 404);

        return view('admin.permissions.edit', [
            'permission' => $permission,
            'modules' => $this->modules(),
            'selectedModule' => str($permission->name)->before('.')->toString(),
        ]);
    }

    public function update(Request $request, Permission $permission): RedirectResponse
    {
        abort_unless($permission->guard_name === $this->guardName(), 404);
        $guard = $this->guardName();
        $data = $this->validatePermission($request, $guard, $permission->id);

        $permission->update([
            'name' => $data['name'],
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('admin.permissions.index')->with('success', 'Permission berhasil diperbarui.');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        abort_unless($permission->guard_name === $this->guardName(), 404);

        Menu::query()
            ->where('permission_name', $permission->name)
            ->update(['permission_name' => null]);

        $permission->delete();
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('admin.permissions.index')->with('success', 'Permission berhasil dihapus.');
    }

    private function guardName(): string
    {
        return (string) config('rbac.guard_name', config('auth.defaults.guard', 'web'));
    }

    private function modules(): array
    {
        return [
            'dashboard',
            'posts',
            'categories',
            'media',
            'users',
            'menus',
            'roles',
            'permissions',
            'settings',
        ];
    }

    private function validatePermission(Request $request, string $guard, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(\.[a-z0-9-]+)+$/',
                Rule::unique('permissions', 'name')
                    ->ignore($ignoreId)
                    ->where(fn ($query) => $query->where('guard_name', $guard)),
            ],
        ], [
            'name.regex' => 'Format permission harus seperti modul.aksi, misalnya users.view.',
        ]);
    }
}
