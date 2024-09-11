<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RuanganSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'program_studi_id' => 1,
                'nama' => 'TI-Ruang 1',
                'kode' => 'TIR1',
                'keterangan' => 'Ruang kelas untuk Teknik Informatika',
                'kapasitas' => 40,
            ],
            [
                'program_studi_id' => 1,
                'nama' => 'TI-Ruang 2',
                'kode' => 'TIR2',
                'keterangan' => 'Ruang kelas untuk Teknik Informatika',
                'kapasitas' => 35,
            ],
            [
                'program_studi_id' => 1,
                'nama' => 'TI-Ruang 3',
                'kode' => 'TIR3',
                'keterangan' => 'Ruang kelas untuk Teknik Informatika',
                'kapasitas' => 50,
            ],
            [
                'program_studi_id' => 1,
                'nama' => 'TI-Ruang 4',
                'kode' => 'TIR4',
                'keterangan' => 'Ruang kelas untuk Teknik Informatika',
                'kapasitas' => 45,
            ],
            [
                'program_studi_id' => 1,
                'nama' => 'TI-Ruang 5',
                'kode' => 'TIR5',
                'keterangan' => 'Ruang kelas untuk Teknik Informatika',
                'kapasitas' => 30,
            ],
            [
                'program_studi_id' => 1,
                'nama' => 'TI-Ruang 6',
                'kode' => 'TIR6',
                'keterangan' => 'Ruang kelas untuk Teknik Informatika',
                'kapasitas' => 60,
            ],
            [
                'program_studi_id' => 1,
                'nama' => 'TI-Ruang 7',
                'kode' => 'TIR7',
                'keterangan' => 'Ruang kelas untuk Teknik Informatika',
                'kapasitas' => 40,
            ],
            [
                'program_studi_id' => 1,
                'nama' => 'TI-Ruang 8',
                'kode' => 'TIR8',
                'keterangan' => 'Ruang kelas untuk Teknik Informatika',
                'kapasitas' => 50,
            ],
            [
                'program_studi_id' => 1,
                'nama' => 'TI-Ruang 9',
                'kode' => 'TIR9',
                'keterangan' => 'Ruang kelas untuk Teknik Informatika',
                'kapasitas' => 55,
            ],
            [
                'program_studi_id' => 1,
                'nama' => 'TI-Ruang 10',
                'kode' => 'TIR10',
                'keterangan' => 'Ruang kelas untuk Teknik Informatika',
                'kapasitas' => 30,
            ],
        ];

        // Insert data ke dalam tabel 'ruangan'
        $this->db->table('ruangan')->insertBatch($data);
    }
}
