<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Seed the application's roles and permissions.
     */
    public function run(): void
    {
        $guard = config('rbac.guard_name', config('auth.defaults.guard', 'web'));
        $permissions = collect(config('rbac.permissions', []))
            ->filter()
            ->unique()
            ->values();

        $roles = collect(config('rbac.roles', []));

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions->each(function (string $permission) use ($guard): void {
            Permission::findOrCreate($permission, $guard);
        });

        Permission::query()
            ->where('guard_name', $guard)
            ->whereNotIn('name', $permissions)
            ->get()
            ->each
            ->delete();

        $allPermissions = Permission::query()
            ->where('guard_name', $guard)
            ->pluck('name');

        $roles->each(function (array $rolePermissions, string $roleName) use ($allPermissions, $guard): void {
            $role = Role::findOrCreate($roleName, $guard);

            $role->syncPermissions($rolePermissions === ['*'] ? $allPermissions : $rolePermissions);
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
