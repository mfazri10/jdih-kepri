<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ThemeSeeder extends Seeder
{
    public function run(): void
    {
        $themes = [
            ['name' => 'Otonomi Daerah',          'color' => '#2563eb', 'icon' => 'bi-building'],
            ['name' => 'Desentralisasi',           'color' => '#7c3aed', 'icon' => 'bi-diagram-3'],
            ['name' => 'Pelayanan Publik',         'color' => '#059669', 'icon' => 'bi-headset'],
            ['name' => 'Anti Korupsi',             'color' => '#dc2626', 'icon' => 'bi-shield-check'],
            ['name' => 'HAM & Gender',             'color' => '#d97706', 'icon' => 'bi-gender-ambiguous'],
            ['name' => 'Kemiskinan',               'color' => '#6366f1', 'icon' => 'bi-coin'],
            ['name' => 'Perizinan',                'color' => '#0891b2', 'icon' => 'bi-file-earmark-check'],
            ['name' => 'Pengadaan Barang/Jasa',    'color' => '#be185d', 'icon' => 'bi-box-seam'],
        ];

        foreach ($themes as $index => $theme) {
            Theme::updateOrCreate(
                ['slug' => Str::slug($theme['name'])],
                array_merge($theme, [
                    'sort_order' => $index + 1,
                    'is_active'  => true,
                ])
            );
        }
    }
}
