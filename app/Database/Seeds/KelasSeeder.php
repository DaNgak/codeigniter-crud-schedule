<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Kelas untuk Teknik Informatika (ID = 1)
            [
                'program_studi_id' => 1,
                'nama' => 'TI Kelas A',
                'kode' => 'TIA',
            ],
            [
                'program_studi_id' => 1,
                'nama' => 'TI Kelas B',
                'kode' => 'TIB',
            ],
            [
                'program_studi_id' => 1,
                'nama' => 'TI Kelas C',
                'kode' => 'TIC',
            ],
            [
                'program_studi_id' => 1,
                'nama' => 'TI Kelas D',
                'kode' => 'TID',
            ],
            [
                'program_studi_id' => 1,
                'nama' => 'TI Kelas E',
                'kode' => 'TIE',
            ],

            // Kelas untuk Teknik Elektro (ID = 2)
            [
                'program_studi_id' => 2,
                'nama' => 'TE Kelas A',
                'kode' => 'TEA',
            ],
            [
                'program_studi_id' => 2,
                'nama' => 'TE Kelas B',
                'kode' => 'TEB',
            ],
            [
                'program_studi_id' => 2,
                'nama' => 'TE Kelas C',
                'kode' => 'TEC',
            ],
            [
                'program_studi_id' => 2,
                'nama' => 'TE Kelas D',
                'kode' => 'TED',
            ],
            [
                'program_studi_id' => 2,
                'nama' => 'TE Kelas E',
                'kode' => 'TEE',
            ],

            // Kelas untuk Teknik Mesin (ID = 3)
            [
                'program_studi_id' => 3,
                'nama' => 'TM Kelas A',
                'kode' => 'TMA',
            ],
            [
                'program_studi_id' => 3,
                'nama' => 'TM Kelas B',
                'kode' => 'TMB',
            ],
            [
                'program_studi_id' => 3,
                'nama' => 'TM Kelas C',
                'kode' => 'TMC',
            ],
            [
                'program_studi_id' => 3,
                'nama' => 'TM Kelas D',
                'kode' => 'TMD',
            ],
            [
                'program_studi_id' => 3,
                'nama' => 'TM Kelas E',
                'kode' => 'TME',
            ],
        ];

        $this->db->table('kelas')->insertBatch($data);
    }
}
