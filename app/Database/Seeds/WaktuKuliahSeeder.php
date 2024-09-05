<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WaktuKuliahSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Senin
            [
                'hari' => 'Senin',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '10:00:00',
            ],
            [
                'hari' => 'Senin',
                'jam_mulai' => '11:00:00',
                'jam_selesai' => '13:00:00',
            ],
            [
                'hari' => 'Senin',
                'jam_mulai' => '14:00:00',
                'jam_selesai' => '16:00:00',
            ],
            // Selasa
            [
                'hari' => 'Selasa',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '10:00:00',
            ],
            [
                'hari' => 'Selasa',
                'jam_mulai' => '11:00:00',
                'jam_selesai' => '13:00:00',
            ],
            [
                'hari' => 'Selasa',
                'jam_mulai' => '14:00:00',
                'jam_selesai' => '16:00:00',
            ],
            // Rabu
            [
                'hari' => 'Rabu',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '10:00:00',
            ],
            [
                'hari' => 'Rabu',
                'jam_mulai' => '11:00:00',
                'jam_selesai' => '13:00:00',
            ],
            [
                'hari' => 'Rabu',
                'jam_mulai' => '14:00:00',
                'jam_selesai' => '16:00:00',
            ],
            // // Kamis
            // [
            //     'hari' => 'Kamis',
            //     'jam_mulai' => '08:00:00',
            //     'jam_selesai' => '10:00:00',
            // ],
            // [
            //     'hari' => 'Kamis',
            //     'jam_mulai' => '11:00:00',
            //     'jam_selesai' => '13:00:00',
            // ],
            // [
            //     'hari' => 'Kamis',
            //     'jam_mulai' => '14:00:00',
            //     'jam_selesai' => '16:00:00',
            // ],
            // // Jumat
            // [
            //     'hari' => 'Jumat',
            //     'jam_mulai' => '09:00:00',
            //     'jam_selesai' => '11:00:00',
            // ],
            // [
            //     'hari' => 'Jumat',
            //     'jam_mulai' => '14:00:00',
            //     'jam_selesai' => '16:00:00',
            // ],
        ];

        // Insert data into waktu_kuliah table
        $this->db->table('waktu_kuliah')->insertBatch($data);
    }
}
