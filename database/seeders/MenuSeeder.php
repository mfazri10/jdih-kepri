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

        // ─── DOKUMEN HUKUM ──────────────────────────────────────
        $dokumen = Menu::query()->updateOrCreate(
            ['nama_menu' => 'Dokumen Hukum', 'menu_induk_id' => null],
            [
                'nama_fitur' => 'documents',
                'alamat_url' => null,
                'route_name' => null,
                'ikon' => 'feather-file-text',
                'tingkatan_menu' => 'parent',
                'urutan' => 40,
                'permission_name' => null,
                'tag' => 'documents,dokumen,hukum,jdih',
                'is_active' => true,
            ],
        );

        Menu::query()->updateOrCreate(
            ['nama_menu' => 'Daftar Dokumen', 'menu_induk_id' => $dokumen->id],
            [
                'nama_fitur' => 'documents',
                'alamat_url' => null,
                'route_name' => 'admin.documents.index',
                'ikon' => 'feather-file',
                'tingkatan_menu' => 'child',
                'urutan' => 41,
                'permission_name' => 'documents.view',
                'tag' => 'documents,dokumen',
                'is_active' => true,
            ],
        );

        Menu::query()->updateOrCreate(
            ['nama_menu' => 'Jenis Dokumen', 'menu_induk_id' => $dokumen->id],
            [
                'nama_fitur' => 'document-types',
                'alamat_url' => null,
                'route_name' => 'admin.document-types.index',
                'ikon' => 'feather-layers',
                'tingkatan_menu' => 'child',
                'urutan' => 42,
                'permission_name' => 'document-types.view',
                'tag' => 'document-types,jenis',
                'is_active' => true,
            ],
        );

        Menu::query()->updateOrCreate(
            ['nama_menu' => 'Kategori', 'menu_induk_id' => $dokumen->id],
            [
                'nama_fitur' => 'categories',
                'alamat_url' => null,
                'route_name' => 'admin.categories.index',
                'ikon' => 'feather-folder',
                'tingkatan_menu' => 'child',
                'urutan' => 43,
                'permission_name' => 'categories.view',
                'tag' => 'categories,kategori',
                'is_active' => true,
            ],
        );

        Menu::query()->updateOrCreate(
            ['nama_menu' => 'Tematik', 'menu_induk_id' => $dokumen->id],
            [
                'nama_fitur' => 'themes',
                'alamat_url' => null,
                'route_name' => 'admin.themes.index',
                'ikon' => 'feather-tag',
                'tingkatan_menu' => 'child',
                'urutan' => 44,
                'permission_name' => 'themes.view',
                'tag' => 'themes,tematik',
                'is_active' => true,
            ],
        );

        Menu::query()->updateOrCreate(
            ['nama_menu' => 'Tag', 'menu_induk_id' => $dokumen->id],
            [
                'nama_fitur' => 'tags',
                'alamat_url' => null,
                'route_name' => 'admin.tags.index',
                'ikon' => 'feather-bookmark',
                'tingkatan_menu' => 'child',
                'urutan' => 45,
                'permission_name' => 'tags.view',
                'tag' => 'tags,label',
                'is_active' => true,
            ],
        );

        // ─── LAYANAN PUBLIK ────────────────────────────────────
        $layanan = Menu::query()->updateOrCreate(
            ['nama_menu' => 'Layanan Publik', 'menu_induk_id' => null],
            [
                'nama_fitur' => 'public-services',
                'alamat_url' => null,
                'route_name' => null,
                'ikon' => 'feather-heart',
                'tingkatan_menu' => 'parent',
                'urutan' => 50,
                'permission_name' => null,
                'tag' => 'consultations,hearings,feedbacks,subscriptions',
                'is_active' => true,
            ],
        );

        Menu::query()->updateOrCreate(
            ['nama_menu' => 'Konsultasi Hukum', 'menu_induk_id' => $layanan->id],
            [
                'nama_fitur' => 'consultations',
                'alamat_url' => null,
                'route_name' => 'admin.consultations.index',
                'ikon' => 'feather-message-circle',
                'tingkatan_menu' => 'child',
                'urutan' => 51,
                'permission_name' => 'consultations.view',
                'tag' => 'consultations,konsultasi',
                'is_active' => true,
            ],
        );

        Menu::query()->updateOrCreate(
            ['nama_menu' => 'Public Hearing', 'menu_induk_id' => $layanan->id],
            [
                'nama_fitur' => 'hearings',
                'alamat_url' => null,
                'route_name' => 'admin.hearings.index',
                'ikon' => 'feather-mic',
                'tingkatan_menu' => 'child',
                'urutan' => 52,
                'permission_name' => 'hearings.view',
                'tag' => 'hearings,hearing',
                'is_active' => true,
            ],
        );

        Menu::query()->updateOrCreate(
            ['nama_menu' => 'Permintaan Informasi', 'menu_induk_id' => $layanan->id],
            [
                'nama_fitur' => 'information-requests',
                'alamat_url' => null,
                'route_name' => 'admin.information-requests.index',
                'ikon' => 'feather-file-plus',
                'tingkatan_menu' => 'child',
                'urutan' => 53,
                'permission_name' => 'information-requests.view',
                'tag' => 'information-requests,informasi',
                'is_active' => true,
            ],
        );

        Menu::query()->updateOrCreate(
            ['nama_menu' => 'Umpan Balik', 'menu_induk_id' => $layanan->id],
            [
                'nama_fitur' => 'feedbacks',
                'alamat_url' => null,
                'route_name' => 'admin.feedbacks.index',
                'ikon' => 'feather-thumbs-up',
                'tingkatan_menu' => 'child',
                'urutan' => 54,
                'permission_name' => 'feedbacks.view',
                'tag' => 'feedbacks,feedback,umpan-balik',
                'is_active' => true,
            ],
        );

        Menu::query()->updateOrCreate(
            ['nama_menu' => 'Langganan', 'menu_induk_id' => $layanan->id],
            [
                'nama_fitur' => 'subscriptions',
                'alamat_url' => null,
                'route_name' => 'admin.subscriptions.index',
                'ikon' => 'feather-bell',
                'tingkatan_menu' => 'child',
                'urutan' => 55,
                'permission_name' => 'subscriptions.view',
                'tag' => 'subscriptions,langganan',
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
