<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create permissions
        $permissions = [
            // Dashboard
            'dashboard.view',
            
            // User Management
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            
            // Role Management
            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',
            
            // Permission Management
            'permissions.view',
            'permissions.create',
            'permissions.update',
            'permissions.delete',
            
            // Menu Management
            'menus.view',
            'menus.create',
            'menus.update',
            'menus.delete',
            
            // Login Logs (Audit Log Login)
            'login-logs.view',
            
            // Settings
            'settings.view',
            'settings.update',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. Create roles and assign permissions
        
        // Super Admin (Full Access)
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Admin OPD
        $adminOpd = Role::firstOrCreate(['name' => 'admin-opd']);
        $adminOpd->givePermissionTo([
            'dashboard.view',
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            'menus.view',
            'login-logs.view',
            'settings.view',
            'settings.update',
        ]);

        // Bendahara
        $bendahara = Role::firstOrCreate(['name' => 'bendahara']);
        $bendahara->givePermissionTo([
            'dashboard.view',
        ]);

        // PPK / Pimpinan
        $ppk = Role::firstOrCreate(['name' => 'ppk-pimpinan']);
        $ppk->givePermissionTo([
            'dashboard.view',
        ]);

        // Operator
        $operator = Role::firstOrCreate(['name' => 'operator']);
        $operator->givePermissionTo([
            'dashboard.view',
            'users.view',
        ]);

        // Pegawai / Viewer
        $pegawai = Role::firstOrCreate(['name' => 'pegawai']);
        $pegawai->givePermissionTo([
            'dashboard.view',
        ]);
    }
}
