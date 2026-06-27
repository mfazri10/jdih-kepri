<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Undang-Undang',       'code' => 'UU',      'sort_order' => 1],
            ['name' => 'Peraturan Pemerintah', 'code' => 'PP',      'sort_order' => 2],
            ['name' => 'Peraturan Presiden',   'code' => 'PERPRES', 'sort_order' => 3],
            ['name' => 'Peraturan Daerah',     'code' => 'PERDA',   'sort_order' => 4],
            ['name' => 'Peraturan Kepala Daerah','code' => 'PERKADA','sort_order' => 5],
            ['name' => 'Peraturan Gubernur',   'code' => 'PERGUB',  'sort_order' => 6],
            ['name' => 'Surat Keputusan',      'code' => 'SK',      'sort_order' => 7],
            ['name' => 'Surat Edaran',         'code' => 'SE',      'sort_order' => 8],
            ['name' => 'Instruksi',            'code' => 'INSTR',   'sort_order' => 9],
            ['name' => 'Surat Himbauan',       'code' => 'HIMBAU',  'sort_order' => 10],
        ];

        foreach ($types as $type) {
            DocumentType::updateOrCreate(
                ['code' => $type['code']],
                $type
            );
        }
    }
}
