<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:users.view', only: ['index']),
            new Middleware('permission:users.create', only: ['create', 'store']),
            new Middleware('permission:users.update', only: ['edit', 'update']),
            new Middleware('permission:users.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));

        $users = User::query()
            ->with(['roles', 'permissions'])
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($builder) use ($search): void {
                    $builder
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create', [
            'user' => new User(),
            'roles' => $this->roles(),
            'permissionGroups' => $this->permissionGroups(),
            'selectedRoles' => [],
            'selectedPermissions' => [],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateUser($request);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'is_active' => (bool) ($data['is_active'] ?? true),
        ]);

        $user->syncRoles($data['roles'] ?? []);
        $user->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user->load(['roles', 'permissions']),
            'roles' => $this->roles(),
            'permissionGroups' => $this->permissionGroups(),
            'selectedRoles' => $user->roles->pluck('name')->all(),
            'selectedPermissions' => $user->permissions->pluck('name')->all(),
            'effectivePermissions' => $user->getAllPermissions()->pluck('name')->sort()->values(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validateUser($request, $user);

        if ($user->is(auth()->user()) && $user->hasRole('super-admin') && ! in_array('super-admin', $data['roles'] ?? [], true)) {
            return back()->withErrors([
                'roles' => 'Akun super-admin yang sedang aktif tidak boleh melepas role super-admin miliknya sendiri.',
            ])->withInput();
        }

        // Prevent active user from deactivating themselves
        if ($user->is(auth()->user()) && ! ($data['is_active'] ?? true)) {
            return back()->withErrors([
                'is_active' => 'Anda tidak dapat menonaktifkan akun Anda sendiri yang sedang aktif.',
            ])->withInput();
        }

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'is_active' => (bool) ($data['is_active'] ?? false),
        ];

        if (filled($data['password'] ?? null)) {
            $payload['password'] = $data['password'];
        }

        $user->update($payload);
        $user->syncRoles($data['roles'] ?? []);
        $user->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->is(auth()->user())) {
            return back()->with('error', 'Akun yang sedang aktif tidak dapat dihapus.');
        }

        if ($user->hasRole('super-admin') && User::role('super-admin')->count() === 1) {
            return back()->with('error', 'User ini adalah super-admin terakhir dan tidak dapat dihapus.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

    private function validateUser(Request $request, ?User $user = null): array
    {
        $guard = $this->guardName();

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8'],
            'is_active' => ['nullable', 'boolean'],
            'roles' => ['nullable', 'array'],
            'roles.*' => [
                'string',
                Rule::exists('roles', 'name')->where(fn ($query) => $query->where('guard_name', $guard)),
            ],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => [
                'string',
                Rule::exists('permissions', 'name')->where(fn ($query) => $query->where('guard_name', $guard)),
            ],
        ]);
    }

    private function roles(): Collection
    {
        return Role::query()
            ->where('guard_name', $this->guardName())
            ->orderBy('name')
            ->get();
    }

    private function permissionGroups(): Collection
    {
        return Permission::query()
            ->where('guard_name', $this->guardName())
            ->orderBy('name')
            ->get()
            ->groupBy(fn (Permission $permission) => str($permission->name)->before('.')->headline());
    }

    private function guardName(): string
    {
        return (string) config('rbac.guard_name', config('auth.defaults.guard', 'web'));
    }
}
