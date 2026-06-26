<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:roles.view', only: ['index']),
            new Middleware('permission:roles.create', only: ['create', 'store']),
            new Middleware('permission:roles.update', only: ['edit', 'update']),
            new Middleware('permission:roles.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));
        $guard = $this->guardName();

        $roles = Role::query()
            ->where('guard_name', $guard)
            ->withCount('permissions')
            ->when($search !== '', fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.roles.index', [
            'roles' => $roles,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.roles.create', [
            'role' => new Role(['guard_name' => $this->guardName()]),
            'permissionGroups' => $this->permissionGroups(),
            'selectedPermissions' => [],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $guard = $this->guardName();
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->where(fn ($query) => $query->where('guard_name', $guard)),
            ],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => [
                'string',
                Rule::exists('permissions', 'name')->where(fn ($query) => $query->where('guard_name', $guard)),
            ],
        ]);

        $role = Role::create([
            'name' => $data['name'],
            'guard_name' => $guard,
        ]);

        $role->syncPermissions($data['permissions'] ?? []);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil ditambahkan.');
    }

    public function edit(Role $role): View
    {
        abort_unless($role->guard_name === $this->guardName(), 404);

        return view('admin.roles.edit', [
            'role' => $role->load('permissions'),
            'permissionGroups' => $this->permissionGroups(),
            'selectedPermissions' => $role->permissions->pluck('name')->all(),
        ]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        abort_unless($role->guard_name === $this->guardName(), 404);
        $guard = $this->guardName();

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')
                    ->ignore($role->id)
                    ->where(fn ($query) => $query->where('guard_name', $guard)),
            ],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => [
                'string',
                Rule::exists('permissions', 'name')->where(fn ($query) => $query->where('guard_name', $guard)),
            ],
        ]);

        $role->update(['name' => $data['name']]);
        $role->syncPermissions($data['permissions'] ?? []);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        abort_unless($role->guard_name === $this->guardName(), 404);

        if ($role->name === 'super-admin') {
            return back()->with('error', 'Role super-admin tidak boleh dihapus.');
        }

        $role->delete();
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil dihapus.');
    }

    private function guardName(): string
    {
        return (string) config('rbac.guard_name', config('auth.defaults.guard', 'web'));
    }

    private function permissionGroups(): Collection
    {
        return Permission::query()
            ->where('guard_name', $this->guardName())
            ->orderBy('name')
            ->get()
            ->groupBy(fn (Permission $permission) => str($permission->name)->before('.')->headline());
    }
}
