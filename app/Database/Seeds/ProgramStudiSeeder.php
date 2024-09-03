<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProgramStudiSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama' => 'Teknik Informatika',
                'kode' => 'TI',
            ],
            [
                'nama' => 'Teknik Elektro',
                'kode' => 'TE',
            ],
            [
                'nama' => 'Teknik Mesin',
                'kode' => 'TM',
            ],
        ];

        $this->db->table('program_studi')->insertBatch($data);
    }
}
