<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Pendidikan',        'icon' => 'bi-mortarboard'],
            ['name' => 'Kesehatan',         'icon' => 'bi-heart-pulse'],
            ['name' => 'Kelautan',          'icon' => 'bi-water'],
            ['name' => 'Pariwisata',        'icon' => 'bi-airplane'],
            ['name' => 'Pertanian',         'icon' => 'bi-flower1'],
            ['name' => 'Perdagangan',       'icon' => 'bi-shop'],
            ['name' => 'Keuangan',          'icon' => 'bi-cash-stack'],
            ['name' => 'Lingkungan Hidup',  'icon' => 'bi-tree'],
            ['name' => 'Tenaga Kerja',      'icon' => 'bi-people'],
            ['name' => 'Perhubungan',       'icon' => 'bi-truck'],
            ['name' => 'Pertanahan',        'icon' => 'bi-geo-alt'],
            ['name' => 'Kependudukan',      'icon' => 'bi-person-vcard'],
        ];

        foreach ($categories as $index => $category) {
            Category::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                array_merge($category, [
                    'sort_order' => $index + 1,
                    'is_active'  => true,
                ])
            );
        }
    }
}
