<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Seed the application's menus.
     */
    public function run(): void
    {
        Menu::query()->updateOrCreate(
            ['nama_menu' => 'Dashboard', 'menu_induk_id' => null],
            [
                'nama_fitur' => 'dashboard',
                'alamat_url' => null,
                'route_name' => 'admin.dashboard',
                'ikon' => 'feather-airplay',
                'tingkatan_menu' => 'parent',
                'urutan' => 10,
                'permission_name' => 'dashboard.view',
                'tag' => 'dashboard,beranda',
                'is_active' => true,
            ],
        );

        $access = Menu::query()->updateOrCreate(
            ['nama_menu' => 'Manajemen Akses', 'menu_induk_id' => null],
            [
                'nama_fitur' => 'rbac',
                'alamat_url' => null,
                'route_name' => null,
                'ikon' => 'feather-shield',
                'tingkatan_menu' => 'parent',
                'urutan' => 20,
                'permission_name' => null,
                'tag' => 'rbac,roles,permissions,users',
                'is_active' => true,
            ],
        );

        Menu::query()->updateOrCreate(
            ['nama_menu' => 'Users', 'menu_induk_id' => $access->id],
            [
                'nama_fitur' => 'users',
                'alamat_url' => null,
                'route_name' => 'admin.users.index',
                'ikon' => 'feather-users',
                'tingkatan_menu' => 'child',
                'urutan' => 21,
                'permission_name' => 'users.view',
                'tag' => 'users,pengguna',
                'is_active' => true,
            ],
        );

        Menu::query()->updateOrCreate(
            ['nama_menu' => 'Roles', 'menu_induk_id' => $access->id],
            [
                'nama_fitur' => 'roles',
                'alamat_url' => null,
                'route_name' => 'admin.roles.index',
                'ikon' => 'feather-award',
                'tingkatan_menu' => 'child',
                'urutan' => 22,
                'permission_name' => 'roles.view',
                'tag' => 'roles,peran',
                'is_active' => true,
            ],
        );

        Menu::query()->updateOrCreate(
            ['nama_menu' => 'Permissions', 'menu_induk_id' => $access->id],
            [
                'nama_fitur' => 'permissions',
                'alamat_url' => null,
                'route_name' => 'admin.permissions.index',
                'ikon' => 'feather-key',
                'tingkatan_menu' => 'child',
                'urutan' => 23,
                'permission_name' => 'permissions.view',
                'tag' => 'permissions,izin',
                'is_active' => true,
            ],
        );

        Menu::query()->updateOrCreate(
            ['nama_menu' => 'Log Login', 'menu_induk_id' => $access->id],
            [
                'nama_fitur' => 'login-logs',
                'alamat_url' => null,
                'route_name' => 'admin.login-logs.index',
                'ikon' => 'feather-activity',
                'tingkatan_menu' => 'child',
                'urutan' => 24,
                'permission_name' => 'login-logs.view',
                'tag' => 'login,logs,history,audit',
                'is_active' => true,
            ],
        );

        $settings = Menu::query()->updateOrCreate(
            ['nama_menu' => 'Pengaturan', 'menu_induk_id' => null],
            [
                'nama_fitur' => 'settings',
                'alamat_url' => null,
                'route_name' => null,
                'ikon' => 'feather-settings',
                'tingkatan_menu' => 'parent',
                'urutan' => 30,
                'permission_name' => null,
                'tag' => 'settings,pengaturan',
                'is_active' => true,
            ],
        );

        Menu::query()->updateOrCreate(
            ['nama_menu' => 'Menu Admin', 'menu_induk_id' => $settings->id],
            [
                'nama_fitur' => 'menus',
                'alamat_url' => null,
                'route_name' => 'admin.menus.index',
                'ikon' => 'feather-menu',
                'tingkatan_menu' => 'child',
                'urutan' => 31,
                'permission_name' => 'menus.view',
                'tag' => 'menus,navigasi',
                'is_active' => true,
            ],
        );
    }
}
